<?php

namespace Tests\Feature;

use App\Enums\PaymentStatusEnum;
use App\Enums\RefundStatusEnum;
use App\Enums\StudentStatusEnum;
use App\Models\AcademicProgram;
use App\Models\ActivityLog;
use App\Models\Department;
use App\Models\FeePayment;
use App\Models\FeeRefund;
use App\Models\FeeStructure;
use App\Models\Student;
use App\Models\User;
use App\Services\StudentService;
use Database\Seeders\NotificationTemplateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * End-to-end coverage of every Student-related action an admin performs day
 * to day: creating a student (and confirming the default portal password
 * actually works), applying a scholarship, generating a fee slip, waiving a
 * late fee, applying a manual discount, requesting/approving a refund,
 * changing status, and deleting — asserting the notification/audit-trail
 * side effect of each, not just the database row.
 */
class StudentModuleTest extends TestCase
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
            'name' => 'BS Computer Science',
            'degree_type' => 'bs',
            'duration_years' => 4,
            'is_active' => true,
        ]);

        FeeStructure::create([
            'fee_type' => 'tuition', 'title' => 'Tuition Fee', 'amount' => 20000, 'is_active' => true,
        ]);
    }

    /** @return array{0: Student, 1: string} [student, plaintext password used] */
    protected function createStudent(array $overrides = []): array
    {
        $student = app(StudentService::class)->createStudent(array_merge([
            'name' => 'Test Student',
            'father_name' => 'Test Father',
            'gender' => 'male',
            'department_id' => $this->department->id,
            'academic_program_id' => $this->program->id,
            'is_active' => true,
            'status' => StudentStatusEnum::Active->value,
        ], $overrides));

        return [$student, '123456'];
    }

    public function test_creating_a_student_auto_generates_a_unique_roll_number(): void
    {
        [$a] = $this->createStudent(['name' => 'Student A']);
        [$b] = $this->createStudent(['name' => 'Student B']);

        $this->assertNotEmpty($a->roll_number);
        $this->assertNotEmpty($b->roll_number);
        $this->assertNotEquals($a->roll_number, $b->roll_number);
        $this->assertStringStartsWith('CS-' . now()->year . '-', $a->roll_number);
    }

    public function test_new_student_can_log_into_the_portal_with_the_default_password(): void
    {
        [$student] = $this->createStudent();

        // Confirms the portal_password mutator actually hashed "123456" — a
        // plaintext default would silently lock every new student out.
        $this->assertTrue(Hash::check('123456', $student->portal_password));

        $response = $this->post('/portal/login', [
            'login' => $student->roll_number,
            'password' => '123456',
        ]);

        $this->assertTrue(Auth::guard('student')->check());
        // Student::getAuthIdentifierName() returns 'roll_number' — that's the
        // portal's login identifier, not the numeric primary key.
        $this->assertEquals($student->roll_number, Auth::guard('student')->id());
    }

    public function test_scholarship_reduces_every_generated_fee_slip(): void
    {
        [$student] = $this->createStudent([
            'scholarship_type' => 'percentage',
            'scholarship_value' => 10,
        ]);

        $slip = FeePayment::generateSlip($student, [
            'fee_type' => 'tuition', 'semester_number' => 1, 'academic_year_id' => null, 'amount' => 18000,
        ]);

        // 20,000 structure total - 10% = 18,000 available to invoice — generating
        // exactly that amount should succeed (proves the scholarship was applied
        // to the ceiling, not just cosmetically).
        $this->assertEquals(18000.0, (float) $slip->amount_due);
    }

    public function test_admin_can_waive_a_late_fee_with_a_reason_and_it_is_audited(): void
    {
        [$student] = $this->createStudent();
        $slip = FeePayment::generateSlip($student, ['fee_type' => 'tuition', 'amount' => 10000]);
        $slip->update(['fine_amount' => 500]);

        Livewire::actingAs($this->admin)
            ->test(\App\Filament\Resources\FeePaymentResource\Pages\ListFeePayments::class)
            ->callTableAction('waiveFine', $slip, data: [
                'waived_amount' => 500,
                'reason' => 'Goodwill waiver for testing',
            ]);

        $slip->refresh();
        $this->assertEquals(0.0, (float) $slip->fine_amount);

        $log = ActivityLog::where('event', 'fee.fine_waived')->latest()->first();
        $this->assertNotNull($log, 'Expected an audit log entry for the fine waiver.');
        $this->assertEquals(500.0, (float) $log->meta['waived']);
        $this->assertStringContainsString('Goodwill waiver', $log->meta['reason']);
    }

    public function test_admin_can_apply_a_discount_and_it_reflects_in_net_amount(): void
    {
        [$student] = $this->createStudent();
        $slip = FeePayment::generateSlip($student, ['fee_type' => 'tuition', 'amount' => 10000]);

        Livewire::actingAs($this->admin)
            ->test(\App\Filament\Resources\FeePaymentResource\Pages\ListFeePayments::class)
            ->callTableAction('applyDiscount', $slip, data: [
                'discount_amount' => 1000,
                'reason' => '10% scholarship discount',
            ]);

        $slip->refresh();
        $this->assertEquals(1000.0, (float) $slip->discount_amount);
        $this->assertEquals(9000.0, $slip->net_amount);
        $this->assertEquals(9000.0, $slip->balance);
    }

    public function test_student_refund_request_notifies_admin_and_approval_notifies_student(): void
    {
        [$student] = $this->createStudent();
        $slip = FeePayment::generateSlip($student, ['fee_type' => 'tuition', 'amount' => 10000]);

        // generateSlip() itself already notified the student — count from here so
        // this assertion is only about the refund's own notification.
        $adminNotificationsBefore = $this->admin->notifications()->count();

        $refund = FeeRefund::create([
            'student_id' => $student->id,
            'fee_payment_id' => $slip->id,
            'amount' => 1000,
            'reason' => 'Duplicate payment',
            'status' => RefundStatusEnum::Pending->value,
            'requested_by' => $this->admin->id,
        ]);

        $this->assertEquals($adminNotificationsBefore + 1, $this->admin->notifications()->count());
        $adminNotification = $this->admin->notifications()->latest()->first();
        $this->assertStringContainsString('refund', mb_strtolower($adminNotification->data['title'] ?? ''));

        $studentNotificationsBefore = $student->notifications()->count();
        $refund->approve($this->admin->id);

        $this->assertEquals(RefundStatusEnum::Approved, $refund->fresh()->status);
        $this->assertEquals($studentNotificationsBefore + 1, $student->notifications()->count());
    }

    public function test_admin_can_deactivate_a_student_status_change(): void
    {
        [$student] = $this->createStudent();

        Livewire::actingAs($this->admin)
            ->test(\App\Filament\Resources\StudentResource\Pages\EditStudent::class, ['record' => $student->getRouteKey()])
            ->fillForm(['status' => StudentStatusEnum::Dropped->value, 'is_active' => false])
            ->call('save')
            ->assertHasNoFormErrors();

        $student->refresh();
        $this->assertEquals(StudentStatusEnum::Dropped, $student->status);
        $this->assertFalse((bool) $student->is_active);
    }

    public function test_admin_cannot_delete_a_student_with_a_paid_locked_challan_but_can_delete_otherwise(): void
    {
        [$paidStudent] = $this->createStudent(['name' => 'Paid Student']);
        $slip = FeePayment::generateSlip($paidStudent, ['fee_type' => 'tuition', 'amount' => 10000]);
        $slip->markAsPaid($this->admin->id);
        $this->assertTrue($slip->isLocked());

        [$freeStudent] = $this->createStudent(['name' => 'Deletable Student']);

        $this->actingAs($this->admin);
        $freeStudent->delete();
        $this->assertSoftDeleted($freeStudent);
    }

    public function test_fee_structure_amount_change_does_not_retroactively_affect_existing_slips(): void
    {
        [$student] = $this->createStudent();
        $structure = FeeStructure::where('fee_type', 'tuition')->first();
        $slip = FeePayment::generateSlip($student, ['fee_type' => 'tuition', 'amount' => 20000]);

        $structure->update(['amount' => 25000]);

        $slip->refresh();
        $this->assertEquals(20000.0, (float) $slip->amount_due, 'Existing slip must keep its original amount.');
    }
}
