<?php

namespace Tests\Feature;

use App\Enums\PaymentStatusEnum;
use App\Models\AcademicProgram;
use App\Models\Department;
use App\Models\FeePayment;
use App\Models\FeeStructure;
use App\Models\Student;
use App\Models\User;
use Database\Seeders\NotificationTemplateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Covers the "Original Fee - Scholarship - Discount = Final Payable" breakdown
 * introduced to replace the old single undifferentiated discount_amount bucket,
 * plus the multi-fee-type challan creation and the admin challan-generated
 * notification that were built alongside it.
 */
class FeeBreakdownTest extends TestCase
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

        $this->department = Department::create(['name' => 'Education', 'code' => 'EDU', 'is_active' => true]);
        $this->program = AcademicProgram::create([
            'department_id' => $this->department->id, 'name' => 'B.Ed', 'degree_type' => 'bed', 'duration_years' => 3, 'is_active' => true,
        ]);
    }

    protected function makeStudent(array $overrides = []): Student
    {
        return Student::create(array_merge([
            'name' => 'Test Student', 'father_name' => 'Test Father', 'gender' => 'male',
            'roll_number' => 'ROLL-' . uniqid(),
            'department_id' => $this->department->id, 'academic_program_id' => $this->program->id,
            'is_active' => true,
        ], $overrides));
    }

    public function test_fee_breakdown_reconstructs_original_fee_for_a_scholarship_student_slip(): void
    {
        FeeStructure::create(['fee_type' => 'tuition', 'title' => 'Tuition', 'amount' => 10000, 'is_active' => true]);
        $student = $this->makeStudent(['scholarship_type' => 'percentage', 'scholarship_value' => 20]);

        // 8000 net = 10000 original - 20% (2000).
        $slip = FeePayment::generateSlip($student, ['fee_type' => 'tuition', 'amount' => 8000]);

        $breakdown = $slip->fee_breakdown;
        $this->assertEquals(10000.0, $breakdown['original_fee']);
        $this->assertEquals(2000.0, $breakdown['scholarship_discount']);
        $this->assertEquals(20.0, $breakdown['scholarship_percent']);
        $this->assertEquals(8000.0, $breakdown['subtotal_after_scholarship']);
        $this->assertEquals(0.0, $breakdown['manual_discount']);
        $this->assertEquals(8000.0, $breakdown['final_payable']);

        // discount_amount stays 0 for this path — amount_due already is net —
        // net_amount/balance math must be completely unaffected.
        $this->assertEquals(0.0, (float) $slip->discount_amount);
        $this->assertEquals(8000.0, $slip->net_amount);
    }

    public function test_reconcile_scholarship_populates_breakdown_snapshot_and_keeps_discount_amount_derived(): void
    {
        FeeStructure::create(['fee_type' => 'tuition', 'title' => 'Tuition', 'amount' => 10000, 'is_active' => true]);
        $student = $this->makeStudent();
        $slip = FeePayment::generateSlip($student, ['fee_type' => 'tuition', 'amount' => 10000]);
        $student->update(['scholarship_type' => 'percentage', 'scholarship_value' => 10]);

        $slip->fresh()->reconcileScholarship($this->admin->id);
        $slip->refresh();

        $breakdown = $slip->fee_breakdown;
        $this->assertEquals(10000.0, $breakdown['original_fee']);
        $this->assertEquals(1000.0, $breakdown['scholarship_discount']);
        $this->assertEquals(9000.0, $breakdown['final_payable']);
        $this->assertEquals(1000.0, (float) $slip->discount_amount);
        $this->assertEquals(9000.0, $slip->net_amount);
    }

    public function test_manual_discount_stacks_on_top_of_scholarship_discount_without_losing_either(): void
    {
        FeeStructure::create(['fee_type' => 'tuition', 'title' => 'Tuition', 'amount' => 10000, 'is_active' => true]);
        $student = $this->makeStudent(['scholarship_type' => 'percentage', 'scholarship_value' => 10]);
        $slip = FeePayment::generateSlip($student, ['fee_type' => 'tuition', 'amount' => 9000]); // 10000 original - 10%

        Livewire::actingAs($this->admin)
            ->test(\App\Filament\Resources\FeePaymentResource\Pages\ListFeePayments::class)
            ->callTableAction('applyDiscount', $slip, data: [
                'manual_discount_amount' => 500,
                'reason' => 'Sibling discount',
            ]);

        $slip->refresh();
        $breakdown = $slip->fee_breakdown;
        $this->assertEquals(1000.0, $breakdown['scholarship_discount']);
        $this->assertEquals(500.0, $breakdown['manual_discount']);
        // The scholarship discount is already baked into amount_due for this
        // (generateSlip) path — discount_amount only ever needs to carry the
        // manual portion here, or the scholarship would be subtracted twice.
        $this->assertEquals(500.0, (float) $slip->discount_amount);
        $this->assertEquals(8500.0, $slip->net_amount);
    }

    public function test_create_fee_payment_page_generates_one_challan_per_selected_fee_type(): void
    {
        FeeStructure::create(['fee_type' => 'tuition', 'title' => 'Tuition', 'amount' => 10000, 'is_active' => true]);
        FeeStructure::create(['fee_type' => 'library', 'title' => 'Library', 'amount' => 1000, 'is_active' => true]);
        $student = $this->makeStudent();

        Livewire::actingAs($this->admin)
            ->test(\App\Filament\Resources\FeePaymentResource\Pages\CreateFeePayment::class)
            ->fillForm([
                'student_id' => $student->id,
                'due_date' => now()->addDays(15)->toDateString(),
                'selected_fee_types' => ['tuition', 'library'],
                'amount_tuition' => 10000,
                'amount_library' => 1000,
            ])
            ->call('create');

        $this->assertEquals(2, FeePayment::where('student_id', $student->id)->count());
        $this->assertTrue(FeePayment::where('student_id', $student->id)->where('fee_type', 'tuition')->exists());
        $this->assertTrue(FeePayment::where('student_id', $student->id)->where('fee_type', 'library')->exists());
    }

    public function test_generating_a_slip_sends_admin_an_in_app_notification_linking_to_the_challan(): void
    {
        FeeStructure::create(['fee_type' => 'tuition', 'title' => 'Tuition', 'amount' => 10000, 'is_active' => true]);
        $student = $this->makeStudent();

        $slip = FeePayment::generateSlip($student, ['fee_type' => 'tuition', 'amount' => 10000]);

        $this->assertEquals(1, $this->admin->notifications()->count());
        $notification = $this->admin->notifications()->first();
        $this->assertStringContainsString($slip->challan_number, $notification->data['body']);
    }
}
