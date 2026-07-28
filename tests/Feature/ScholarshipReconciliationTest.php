<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\AcademicProgram;
use App\Models\Department;
use App\Models\FeePayment;
use App\Models\FeeStructure;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Reproduces the exact real-world case that surfaced this gap: a student's
 * scholarship is added AFTER a challan already exists at the full flat rate,
 * so the discount never gets applied automatically — this covers the fix
 * (a trackable scholarship_applied flag + a way to catch up existing
 * challans) rather than just the happy path where scholarship is set first.
 */
class ScholarshipReconciliationTest extends TestCase
{
    use RefreshDatabase;

    protected Department $department;
    protected AcademicProgram $program;
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
        $this->admin = User::factory()->create();
        $this->admin->assignRole('super_admin');

        $this->department = Department::create(['name' => 'Education', 'code' => 'EDU', 'is_active' => true]);
        $this->program = AcademicProgram::create([
            'department_id' => $this->department->id, 'name' => 'B.Ed 2.5 Year',
            'degree_type' => 'bed', 'duration_years' => 3, 'is_active' => true,
        ]);
        FeeStructure::create(['fee_type' => 'tuition', 'title' => 'Tuition', 'amount' => 10000, 'is_active' => true]);
    }

    public function test_a_slip_generated_while_scholarship_is_active_is_flagged_applied_automatically(): void
    {
        $student = Student::create([
            'name' => 'Saqlain', 'father_name' => 'Raza', 'gender' => 'male', 'roll_number' => 'ROLL-1',
            'department_id' => $this->department->id, 'academic_program_id' => $this->program->id,
            'is_active' => true, 'scholarship_type' => 'percentage', 'scholarship_value' => 10,
        ]);

        $slip = FeePayment::generateSlip($student, ['fee_type' => 'tuition', 'amount' => 9000]);

        $this->assertTrue($slip->scholarship_applied);
        $this->assertEquals(9000.0, (float) $slip->amount_due);
        $this->assertEquals(0.0, (float) $slip->discount_amount);
    }

    public function test_a_challan_created_before_scholarship_was_set_is_flagged_not_applied(): void
    {
        // Exactly the real scenario: challan generated at the full flat rate
        // first, scholarship added to the student's profile afterward.
        $student = Student::create([
            'name' => 'Saqlain', 'father_name' => 'Raza', 'gender' => 'male', 'roll_number' => 'ROLL-2',
            'department_id' => $this->department->id, 'academic_program_id' => $this->program->id,
            'is_active' => true,
        ]);
        $slip = FeePayment::generateSlip($student, ['fee_type' => 'tuition', 'amount' => 10000]);
        $this->assertFalse($slip->scholarship_applied);

        $student->update(['scholarship_type' => 'percentage', 'scholarship_value' => 10]);

        $slip->refresh();
        $this->assertFalse($slip->scholarship_applied, 'Setting the scholarship afterward must not retroactively change an existing challan by itself.');
    }

    public function test_reconcile_scholarship_applies_the_missing_discount_and_is_audited(): void
    {
        $student = Student::create([
            'name' => 'Saqlain', 'father_name' => 'Raza', 'gender' => 'male', 'roll_number' => 'ROLL-3',
            'department_id' => $this->department->id, 'academic_program_id' => $this->program->id,
            'is_active' => true,
        ]);
        $slip = FeePayment::generateSlip($student, ['fee_type' => 'tuition', 'amount' => 10000]);
        $student->update(['scholarship_type' => 'percentage', 'scholarship_value' => 10]);

        $discount = $slip->fresh()->reconcileScholarship($this->admin->id);

        $this->assertEquals(1000.0, $discount);
        $slip->refresh();
        $this->assertEquals(1000.0, (float) $slip->discount_amount);
        $this->assertEquals(9000.0, $slip->net_amount);
        $this->assertTrue($slip->scholarship_applied);

        $log = ActivityLog::where('event', 'fee.scholarship_reconciled')->latest()->first();
        $this->assertNotNull($log);
        $this->assertEquals(1000.0, (float) $log->meta['discount']);
    }

    public function test_reconciling_twice_does_not_double_discount(): void
    {
        $student = Student::create([
            'name' => 'Saqlain', 'father_name' => 'Raza', 'gender' => 'male', 'roll_number' => 'ROLL-4',
            'department_id' => $this->department->id, 'academic_program_id' => $this->program->id,
            'is_active' => true, 'scholarship_type' => 'percentage', 'scholarship_value' => 10,
        ]);
        $slip = FeePayment::generateSlip($student, ['fee_type' => 'tuition', 'amount' => 9000]);
        // Simulate a pre-existing challan that predates the flag ever being set.
        $slip->update(['scholarship_applied' => false]);

        $first = $slip->fresh()->reconcileScholarship($this->admin->id);
        $second = $slip->fresh()->reconcileScholarship($this->admin->id);

        $this->assertGreaterThan(0, $first);
        $this->assertEquals(0.0, $second);
    }

    public function test_bulk_reconcile_action_fixes_every_affected_student_at_once(): void
    {
        $fixedStudent = Student::create([
            'name' => 'Needs Fixing', 'father_name' => 'Father', 'gender' => 'male', 'roll_number' => 'ROLL-5',
            'department_id' => $this->department->id, 'academic_program_id' => $this->program->id, 'is_active' => true,
        ]);
        $slip1 = FeePayment::generateSlip($fixedStudent, ['fee_type' => 'tuition', 'amount' => 10000]);
        $fixedStudent->update(['scholarship_type' => 'fixed', 'scholarship_value' => 2000]);

        $alreadyOkStudent = Student::create([
            'name' => 'Already OK', 'father_name' => 'Father', 'gender' => 'male', 'roll_number' => 'ROLL-6',
            'department_id' => $this->department->id, 'academic_program_id' => $this->program->id,
            'is_active' => true, 'scholarship_type' => 'percentage', 'scholarship_value' => 10,
        ]);
        $slip2 = FeePayment::generateSlip($alreadyOkStudent, ['fee_type' => 'tuition', 'amount' => 9000]);

        Livewire::actingAs($this->admin)
            ->test(\App\Filament\Resources\FeePaymentResource\Pages\ListFeePayments::class)
            ->callAction('reconcileScholarships');

        $this->assertEquals(2000.0, (float) $slip1->fresh()->discount_amount);
        $this->assertTrue($slip1->fresh()->scholarship_applied);
        // The already-correct student's slip must be untouched (no double discount).
        $this->assertEquals(0.0, (float) $slip2->fresh()->discount_amount);
    }

    public function test_student_ledger_reconcile_button_fixes_only_that_students_challans(): void
    {
        $student = Student::create([
            'name' => 'Saqlain', 'father_name' => 'Raza', 'gender' => 'male', 'roll_number' => 'DEPT-EDU-2026-0001',
            'registration_number' => '2024-KIU-ADP-0000',
            'department_id' => $this->department->id, 'academic_program_id' => $this->program->id, 'is_active' => true,
        ]);
        $slip = FeePayment::generateSlip($student, ['fee_type' => 'tuition', 'amount' => 10000]);
        $student->update(['scholarship_type' => 'percentage', 'scholarship_value' => 10]);

        Livewire::actingAs($this->admin)
            ->test(\App\Filament\Pages\StudentLedger::class)
            ->set('q', $student->registration_number)
            ->call('search')
            ->call('reconcileScholarship');

        $slip->refresh();
        $this->assertEquals(1000.0, (float) $slip->discount_amount);
        $this->assertTrue($slip->scholarship_applied);
    }
}
