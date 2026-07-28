<?php

namespace Tests\Feature;

use App\Enums\ScholarshipStatusEnum;
use App\Models\AcademicProgram;
use App\Models\Department;
use App\Models\Scholarship;
use App\Models\ScholarshipAward;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

/**
 * Scholarships can be time-bound (expiry_date) or removed by an admin — this
 * covers the previously-missing reverse sync: approving pushes the value
 * onto the student, but rejecting/expiring must also be able to pull it back
 * off (unless another award is still active), which didn't happen before.
 */
class ScholarshipAwardLifecycleTest extends TestCase
{
    use RefreshDatabase;

    protected Department $department;
    protected AcademicProgram $program;

    protected function setUp(): void
    {
        parent::setUp();

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

    public function test_rejecting_the_only_active_award_clears_the_students_scholarship_fields(): void
    {
        $student = $this->makeStudent();
        $scholarship = Scholarship::create(['name' => 'Merit', 'slug' => 'merit-' . uniqid(), 'coverage_percent' => 20, 'is_active' => true]);
        $award = ScholarshipAward::create([
            'scholarship_id' => $scholarship->id, 'student_id' => $student->id,
            'status' => ScholarshipStatusEnum::Approved, 'application_date' => now(), 'approval_date' => now(),
        ]);

        $this->assertEquals('percentage', $student->fresh()->scholarship_type);
        $this->assertEquals(20.0, (float) $student->fresh()->scholarship_value);

        $award->update(['status' => ScholarshipStatusEnum::Rejected]);

        $student->refresh();
        $this->assertNull($student->scholarship_type);
        $this->assertNull($student->scholarship_value);
    }

    public function test_rejecting_one_award_does_not_clear_the_field_while_another_award_is_still_active(): void
    {
        $student = $this->makeStudent();
        $scholarshipA = Scholarship::create(['name' => 'Merit', 'slug' => 'merit-' . uniqid(), 'coverage_percent' => 20, 'is_active' => true]);
        $scholarshipB = Scholarship::create(['name' => 'Need-Based', 'slug' => 'need-' . uniqid(), 'amount' => 3000, 'is_active' => true]);

        $awardA = ScholarshipAward::create([
            'scholarship_id' => $scholarshipA->id, 'student_id' => $student->id,
            'status' => ScholarshipStatusEnum::Approved, 'application_date' => now(), 'approval_date' => now(),
        ]);
        ScholarshipAward::create([
            'scholarship_id' => $scholarshipB->id, 'student_id' => $student->id,
            'status' => ScholarshipStatusEnum::Approved, 'application_date' => now(), 'approval_date' => now(),
        ]);

        $awardA->update(['status' => ScholarshipStatusEnum::Rejected]);

        $this->assertNotNull($student->fresh()->scholarship_type);
    }

    public function test_expire_command_expires_past_due_awards_and_reverts_the_students_scholarship(): void
    {
        $student = $this->makeStudent();
        $scholarship = Scholarship::create(['name' => 'One Semester', 'slug' => 'sem-' . uniqid(), 'coverage_percent' => 15, 'is_active' => true]);
        $award = ScholarshipAward::create([
            'scholarship_id' => $scholarship->id, 'student_id' => $student->id,
            'status' => ScholarshipStatusEnum::Approved, 'application_date' => now()->subMonths(6),
            'approval_date' => now()->subMonths(6), 'expiry_date' => now()->subDay()->toDateString(),
        ]);

        $this->assertEquals('percentage', $student->fresh()->scholarship_type);

        Artisan::call('scholarships:expire-awards');

        $this->assertEquals(ScholarshipStatusEnum::Expired, $award->fresh()->status);
        $this->assertNull($student->fresh()->scholarship_type);
    }

    public function test_expire_command_leaves_future_expiring_awards_untouched(): void
    {
        $student = $this->makeStudent();
        $scholarship = Scholarship::create(['name' => 'Full Year', 'slug' => 'year-' . uniqid(), 'coverage_percent' => 10, 'is_active' => true]);
        $award = ScholarshipAward::create([
            'scholarship_id' => $scholarship->id, 'student_id' => $student->id,
            'status' => ScholarshipStatusEnum::Approved, 'application_date' => now(),
            'approval_date' => now(), 'expiry_date' => now()->addMonths(6)->toDateString(),
        ]);

        Artisan::call('scholarships:expire-awards');

        $this->assertEquals(ScholarshipStatusEnum::Approved, $award->fresh()->status);
        $this->assertEquals('percentage', $student->fresh()->scholarship_type);
    }
}
