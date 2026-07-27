<?php

namespace Tests\Feature;

use App\Enums\PaymentStatusEnum;
use App\Enums\RefundStatusEnum;
use App\Models\AcademicProgram;
use App\Models\ActivityLog;
use App\Models\CollegeSetting;
use App\Models\Department;
use App\Models\FeePayment;
use App\Models\FeeRefund;
use App\Models\FeeStructure;
use App\Models\FeeStructureRevision;
use App\Models\Student;
use App\Models\User;
use Database\Seeders\NotificationTemplateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Finance-specific coverage not already exercised by the Student module
 * suite: fee structure versioning, bulk dept-wise challan generation,
 * installment plan splitting, the overdue-fees cron, the due-reminder cron,
 * and the full proof-upload → review → confirm/reject cycle.
 */
class FinanceModuleTest extends TestCase
{
    use RefreshDatabase;

    protected Department $department;
    protected AcademicProgram $program;
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(NotificationTemplateSeeder::class);

        Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
        $this->admin = User::factory()->create();
        $this->admin->assignRole('super_admin');

        $this->department = Department::create([
            'name' => 'Computer Science', 'code' => 'CS', 'is_active' => true,
        ]);
        $this->program = AcademicProgram::create([
            'department_id' => $this->department->id,
            'name' => 'BS Computer Science', 'degree_type' => 'bs', 'duration_years' => 4, 'is_active' => true,
        ]);
    }

    protected function makeStudent(array $overrides = []): Student
    {
        return Student::create(array_merge([
            'name' => 'Test Student', 'father_name' => 'Test Father', 'gender' => 'male',
            'roll_number' => 'ROLL-' . uniqid(),
            'department_id' => $this->department->id, 'academic_program_id' => $this->program->id,
            'is_active' => true, 'status' => 'active',
        ], $overrides));
    }

    // ── Fee Structure versioning ────────────────────────────────────────────

    public function test_update_amount_action_creates_a_revision_and_does_not_touch_existing_challans(): void
    {
        $structure = FeeStructure::create(['fee_type' => 'tuition', 'title' => 'Tuition', 'amount' => 20000, 'is_active' => true]);
        $student = $this->makeStudent();
        $slip = FeePayment::generateSlip($student, ['fee_type' => 'tuition', 'amount' => 20000]);

        Livewire::actingAs($this->admin)
            ->test(\App\Filament\Resources\FeeStructureResource\Pages\ListFeeStructures::class)
            ->callTableAction('updateAmount', $structure, data: [
                'new_amount' => 25000,
                'effective_from' => now()->toDateString(),
                'reason' => 'Annual fee increase',
            ]);

        $structure->refresh();
        $this->assertEquals(25000.0, (float) $structure->amount);

        $revision = FeeStructureRevision::where('fee_structure_id', $structure->id)->first();
        $this->assertNotNull($revision);
        $this->assertEquals(20000.0, (float) $revision->old_amount);
        $this->assertEquals(25000.0, (float) $revision->new_amount);

        // The challan generated before the increase must keep its original amount.
        $this->assertEquals(20000.0, (float) $slip->fresh()->amount_due);
    }

    // ── Bulk dept-wise challan generation ────────────────────────────────────

    public function test_bulk_dept_wise_generation_skips_students_who_already_have_an_unpaid_challan_and_applies_scholarship(): void
    {
        // Without a matching FeeStructure, generateSlip() would reject every
        // amount as "exceeds available balance" — silently caught by the bulk
        // action's own try/catch and miscounted as "already billed."
        FeeStructure::create(['fee_type' => 'tuition', 'title' => 'Tuition', 'amount' => 20000, 'is_active' => true]);

        $full = $this->makeStudent(['name' => 'Full Fee Student']);
        $scholarshipStudent = $this->makeStudent([
            'name' => 'Scholarship Student', 'scholarship_type' => 'percentage', 'scholarship_value' => 10,
        ]);
        $alreadyBilled = $this->makeStudent(['name' => 'Already Billed Student']);
        FeePayment::create([
            'student_id' => $alreadyBilled->id, 'challan_number' => 'CHN-EXIST', 'fee_type' => 'tuition',
            'amount_due' => 20000, 'amount_paid' => 0, 'payment_status' => PaymentStatusEnum::Pending->value,
            'due_date' => now()->addDays(10),
        ]);

        Livewire::actingAs($this->admin)
            ->test(\App\Filament\Resources\FeePaymentResource\Pages\ListFeePayments::class)
            ->mountAction('generateDeptChallans')
            ->setActionData([
                'department_id' => $this->department->id,
                'fee_type' => 'tuition',
                'amount_due' => 20000,
                'due_date' => now()->addDays(15)->toDateString(),
            ])
            ->callMountedAction()
            ->assertHasNoActionErrors();

        $this->assertEquals(20000.0, (float) FeePayment::where('student_id', $full->id)->sole()->amount_due);

        // 10% off 20,000 = 18,000 — proves the scholarship was applied automatically
        // during bulk generation, not just individual slip generation.
        $this->assertEquals(18000.0, (float) FeePayment::where('student_id', $scholarshipStudent->id)->sole()->amount_due);

        // The student who already had an unpaid challan must not get a second one.
        $this->assertEquals(1, FeePayment::where('student_id', $alreadyBilled->id)->count());
    }

    // ── Installment plan splitting ───────────────────────────────────────────

    public function test_installment_plan_splits_evenly_and_respects_the_three_pending_cap(): void
    {
        FeeStructure::create(['fee_type' => 'tuition', 'title' => 'Tuition', 'amount' => 20000, 'is_active' => true]);
        $student = $this->makeStudent();

        $slips = FeePayment::generateInstallmentPlan($student, [
            'fee_type' => 'tuition', 'semester_number' => 1, 'academic_year_id' => null, 'amount' => 10000,
        ], installments: 3);

        $this->assertCount(3, $slips);
        $this->assertEquals(10000.0, array_sum(array_map(fn ($s) => (float) $s->amount_due, $slips)), '', 0.01);
        // Each installment gets its own sequential due date (30-day default gap).
        $this->assertTrue($slips[1]->due_date->gt($slips[0]->due_date));
        $this->assertTrue($slips[2]->due_date->gt($slips[1]->due_date));

        // A 4th installment now would exceed the 3-unpaid cap already enforced by generateSlip().
        $this->expectException(\InvalidArgumentException::class);
        FeePayment::generateSlip($student, ['fee_type' => 'tuition', 'semester_number' => 1, 'amount' => 500]);
    }

    // ── Overdue fees cron ─────────────────────────────────────────────────────

    public function test_check_overdue_fees_command_marks_overdue_applies_fine_and_notifies_once(): void
    {
        CollegeSetting::query()->updateOrCreate(['key' => 'fee_grace_days'], ['value' => '0', 'group' => 'fee']);
        CollegeSetting::query()->updateOrCreate(['key' => 'fee_late_fine_per_day'], ['value' => '100', 'group' => 'fee']);

        $student = $this->makeStudent();
        $slip = FeePayment::create([
            'student_id' => $student->id, 'challan_number' => 'CHN-OVERDUE', 'fee_type' => 'tuition',
            'amount_due' => 10000, 'amount_paid' => 0, 'payment_status' => PaymentStatusEnum::Pending->value,
            'due_date' => now()->subDays(5),
        ]);

        Artisan::call('fees:check-overdue');

        $slip->refresh();
        $this->assertEquals(PaymentStatusEnum::Overdue, $slip->payment_status);
        $this->assertEquals(500.0, (float) $slip->fine_amount); // 5 days late × Rs.100/day
        $this->assertEquals(1, $student->notifications()->count());

        // Running it again the same day must not send a second "just became overdue"
        // notification for the same challan.
        Artisan::call('fees:check-overdue');
        $this->assertEquals(1, $student->fresh()->notifications()->count());
    }

    // ── Due-date reminder cron ────────────────────────────────────────────────

    public function test_send_fee_due_reminders_notifies_once_per_challan(): void
    {
        $student = $this->makeStudent();
        $slip = FeePayment::create([
            'student_id' => $student->id, 'challan_number' => 'CHN-DUESOON', 'fee_type' => 'tuition',
            'amount_due' => 10000, 'amount_paid' => 0, 'payment_status' => PaymentStatusEnum::Pending->value,
            'due_date' => now()->addDays(2),
        ]);

        Artisan::call('fees:send-due-reminders');
        $slip->refresh();
        $this->assertNotNull($slip->due_reminder_sent_at);
        $this->assertEquals(1, $student->notifications()->count());

        Artisan::call('fees:send-due-reminders');
        $this->assertEquals(1, $student->fresh()->notifications()->count());
    }

    // ── Proof upload → review → confirm/reject cycle ────────────────────────

    public function test_full_proof_upload_review_and_confirm_cycle(): void
    {
        $student = $this->makeStudent();
        $slip = FeePayment::create([
            'student_id' => $student->id, 'challan_number' => 'CHN-PROOF', 'fee_type' => 'tuition',
            'amount_due' => 10000, 'amount_paid' => 0, 'payment_status' => PaymentStatusEnum::Pending->value,
            'due_date' => now()->addDays(10),
        ]);

        $this->actingAs($student, 'student');
        \Illuminate\Http\UploadedFile::fake()->create('receipt.pdf', 10, 'application/pdf');
        $this->post("/portal/fees/{$slip->id}/proof", [
            'proof' => \Illuminate\Http\UploadedFile::fake()->create('receipt.pdf', 10, 'application/pdf'),
            'amount' => 10000,
            'deposit_date' => now()->toDateString(),
        ]);

        $slip->refresh();
        $this->assertNotNull($slip->payment_proof_path);
        $this->assertEquals(1, $this->admin->notifications()->count());

        // Admin's Proof Review page must list it, with a badge count of 1.
        $this->assertEquals('1', \App\Filament\Pages\ProofReview::getNavigationBadge());

        Livewire::actingAs($this->admin)
            ->test(\App\Filament\Pages\ProofReview::class)
            ->callTableAction('confirmProofPayment', $slip, data: [
                'payment_date' => now()->toDateString(),
                'payment_method' => 'bank_draft',
            ]);

        $slip->refresh();
        $this->assertEquals(PaymentStatusEnum::Paid, $slip->payment_status);
        $this->assertNotNull($slip->receipt_number);
        $this->assertNull(\App\Filament\Pages\ProofReview::getNavigationBadge());
    }

    public function test_admin_can_reject_a_proof_and_it_is_audited(): void
    {
        $student = $this->makeStudent();
        $slip = FeePayment::create([
            'student_id' => $student->id, 'challan_number' => 'CHN-BADPROOF', 'fee_type' => 'tuition',
            'amount_due' => 10000, 'amount_paid' => 0, 'payment_status' => PaymentStatusEnum::Pending->value,
            'due_date' => now()->addDays(10),
            'payment_proof_path' => 'payment-proofs/fake.pdf',
            'proof_uploaded_at' => now(), 'proof_claimed_amount' => 10000, 'proof_claimed_date' => now(),
        ]);

        Livewire::actingAs($this->admin)
            ->test(\App\Filament\Pages\ProofReview::class)
            ->callTableAction('rejectProof', $slip, data: ['reason' => 'Blurry receipt, cannot verify amount']);

        $slip->refresh();
        $this->assertNull($slip->payment_proof_path);
        $this->assertNull($slip->proof_claimed_amount);
        $this->assertEquals(PaymentStatusEnum::Pending, $slip->payment_status);

        $log = ActivityLog::where('event', 'fee.proof_rejected')->latest()->first();
        $this->assertNotNull($log);
        $this->assertStringContainsString('Blurry receipt', $log->meta['reason']);
    }

    // ── Refund reject path (approve path already covered by Student suite) ──

    public function test_admin_can_reject_a_refund_request(): void
    {
        $student = $this->makeStudent();
        $refund = FeeRefund::create([
            'student_id' => $student->id, 'amount' => 500, 'reason' => 'Test',
            'status' => RefundStatusEnum::Pending->value, 'requested_by' => $this->admin->id,
        ]);

        Livewire::actingAs($this->admin)
            ->test(\App\Filament\Resources\FeeRefundResource\Pages\ListFeeRefunds::class)
            ->callTableAction('reject', $refund, data: ['remarks' => 'Not eligible']);

        $refund->refresh();
        $this->assertEquals(RefundStatusEnum::Rejected, $refund->status);
        $this->assertEquals($this->admin->id, $refund->approved_by);
    }
}
