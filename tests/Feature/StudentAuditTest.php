<?php

namespace Tests\Feature;

use App\Models\AcademicProgram;
use App\Models\Department;
use App\Models\FeePayment;
use App\Models\FeeStructure;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * The Student Audit page aggregates ActivityLog rows recorded directly
 * against the student (e.g. status changes) and against their fee challans
 * (discounts, scholarship reconciliation) into one chronological view, with
 * a PDF export — covers both surfacing the right rows and excluding another
 * student's activity.
 */
class StudentAuditTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Department $department;
    protected AcademicProgram $program;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
        $this->admin = User::factory()->create();
        $this->admin->assignRole('super_admin');

        $this->department = Department::create(['name' => 'Education', 'code' => 'EDU', 'is_active' => true]);
        $this->program = AcademicProgram::create([
            'department_id' => $this->department->id, 'name' => 'B.Ed', 'degree_type' => 'bed', 'duration_years' => 3, 'is_active' => true,
        ]);
        FeeStructure::create(['fee_type' => 'tuition', 'title' => 'Tuition', 'amount' => 10000, 'is_active' => true]);
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

    public function test_audit_page_shows_activity_for_the_students_own_challans_and_excludes_other_students(): void
    {
        $student = $this->makeStudent(['name' => 'Saqlain']);
        $otherStudent = $this->makeStudent(['name' => 'Other Student']);

        $slip = FeePayment::generateSlip($student, ['fee_type' => 'tuition', 'amount' => 10000]);
        $student->update(['scholarship_type' => 'percentage', 'scholarship_value' => 10]);
        $slip->fresh()->reconcileScholarship($this->admin->id);

        $otherSlip = FeePayment::generateSlip($otherStudent, ['fee_type' => 'tuition', 'amount' => 10000]);
        $otherStudent->update(['scholarship_type' => 'percentage', 'scholarship_value' => 10]);
        $otherSlip->fresh()->reconcileScholarship($this->admin->id);

        // Livewire::test()'s mount doesn't populate request()->query() for a
        // real query string — use a genuine HTTP request instead (the page
        // reads request()->query('student') in mount()).
        $response = $this->actingAs($this->admin)->get('/admin/student-audit?student=' . $student->id);

        $response->assertOk();
        $response->assertSee($slip->challan_number);
        $response->assertDontSee($otherSlip->challan_number);
    }

    public function test_pdf_export_route_renders_for_an_authenticated_admin(): void
    {
        $student = $this->makeStudent();
        FeePayment::generateSlip($student, ['fee_type' => 'tuition', 'amount' => 10000]);

        $response = $this->actingAs($this->admin)->get(route('pdf.student-audit', $student));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
