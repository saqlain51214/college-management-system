<?php

namespace Tests\Feature\Portal;

use App\Mail\StudentPortalPasswordChangedMail;
use App\Models\AcademicProgram;
use App\Models\AcademicYear;
use App\Models\Announcement;
use App\Models\Course;
use App\Models\Department;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\FeePayment;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Timetable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

class StudentPortalControllerTest extends TestCase
{
    use RefreshDatabase;

    private Student $student;
    private Department $department;
    private AcademicYear $academicYear;
    private AcademicProgram $program;

    protected function setUp(): void
    {
        parent::setUp();

        [$this->student, $this->department, $this->academicYear, $this->program] = $this->createStudentContext();
    }

    public function test_dashboard_computes_fee_stats_and_filters_published_results_and_student_notices(): void
    {
        FeePayment::factory()->create([
            'student_id' => $this->student->id,
            'amount_due' => 12000,
            'amount_paid' => 8000,
            'payment_status' => 'overdue',
        ]);
        FeePayment::factory()->create([
            'student_id' => $this->student->id,
            'amount_due' => 7000,
            'amount_paid' => 7000,
            'payment_status' => 'paid',
        ]);

        $publishedExam = $this->createExam('Midterm', true);
        $hiddenExam = $this->createExam('Hidden Final', false);

        ExamResult::create([
            'student_id' => $this->student->id,
            'exam_id' => $publishedExam->id,
            'marks_obtained' => 85,
            'grade' => 'A',
            'grade_points' => 3.8,
            'is_absent' => false,
            'is_exempted' => false,
        ]);
        ExamResult::create([
            'student_id' => $this->student->id,
            'exam_id' => $hiddenExam->id,
            'marks_obtained' => 65,
            'grade' => 'C',
            'grade_points' => 2.0,
            'is_absent' => false,
            'is_exempted' => false,
        ]);

        Announcement::factory()->create([
            'title' => 'Student Notice',
            'audience' => 'students',
            'is_published' => true,
        ]);
        Announcement::factory()->create([
            'title' => 'All Notice',
            'audience' => 'all',
            'is_published' => true,
        ]);
        Announcement::factory()->create([
            'title' => 'Staff Notice',
            'audience' => 'staff',
            'is_published' => true,
        ]);
        Announcement::factory()->expired()->create([
            'title' => 'Expired Notice',
            'audience' => 'students',
            'is_published' => true,
        ]);

        $response = $this->asStudent()->get(route('portal.dashboard'));

        $response->assertOk()
            ->assertViewHas('feeStats', fn (array $stats) => $stats['total_due'] == 19000.0
                && $stats['total_paid'] == 15000.0
                && $stats['overdue'] === 1
                && $stats['balance'] == 4000.0)
            ->assertViewHas('recentResults', fn (Collection $results) => $results->count() === 1
                && $results->first()->exam->title === 'Midterm')
            ->assertViewHas('avgGpa', 3.8)
            ->assertViewHas('notices', fn (Collection $notices) => $notices->pluck('title')->contains('Student Notice')
                && $notices->pluck('title')->contains('All Notice')
                && ! $notices->pluck('title')->contains('Staff Notice')
                && ! $notices->pluck('title')->contains('Expired Notice'));
    }

    public function test_results_page_groups_only_published_results_for_authenticated_student(): void
    {
        $semesterOneExam = $this->createExam('Semester One Midterm', true, 1);
        $semesterTwoExam = $this->createExam('Semester Two Final', true, 2);
        $hiddenExam = $this->createExam('Unpublished Result', false, 2);

        ExamResult::create([
            'student_id' => $this->student->id,
            'exam_id' => $semesterOneExam->id,
            'marks_obtained' => 75,
            'grade' => 'B',
            'grade_points' => 3.0,
            'is_absent' => false,
            'is_exempted' => false,
        ]);
        ExamResult::create([
            'student_id' => $this->student->id,
            'exam_id' => $semesterTwoExam->id,
            'marks_obtained' => 90,
            'grade' => 'A',
            'grade_points' => 4.0,
            'is_absent' => false,
            'is_exempted' => false,
        ]);
        ExamResult::create([
            'student_id' => $this->student->id,
            'exam_id' => $hiddenExam->id,
            'marks_obtained' => 55,
            'grade' => 'D',
            'grade_points' => 1.0,
            'is_absent' => false,
            'is_exempted' => false,
        ]);

        $response = $this->asStudent()->get(route('portal.results'));

        $response->assertOk()
            ->assertViewHas('results', fn (Collection $results) => $results->count() === 2
                && $results->pluck('exam.title')->contains('Semester One Midterm')
                && ! $results->pluck('exam.title')->contains('Unpublished Result'))
            ->assertViewHas('grouped', fn (Collection $grouped) => $grouped->keys()->sort()->values()->all() === [1, 2])
            ->assertViewHas('cgpa', 3.5);
    }

    public function test_fees_page_shows_only_student_payments_and_summary_with_fine(): void
    {
        FeePayment::factory()->create([
            'student_id' => $this->student->id,
            'challan_number' => 'CH-OWN-1',
            'amount_due' => 10000,
            'amount_paid' => 6000,
            'fine_amount' => 500,
        ]);
        FeePayment::factory()->create([
            'student_id' => $this->student->id,
            'challan_number' => 'CH-OWN-2',
            'amount_due' => 4000,
            'amount_paid' => 4000,
            'fine_amount' => 0,
        ]);
        FeePayment::factory()->create([
            'student_id' => Student::factory()->create()->id,
            'challan_number' => 'CH-OTHER',
            'amount_due' => 9999,
        ]);

        $response = $this->asStudent()->get(route('portal.fees'));

        $response->assertOk()
            ->assertSee('CH-OWN-1')
            ->assertSee('CH-OWN-2')
            ->assertDontSee('CH-OTHER')
            ->assertViewHas('summary', fn (array $summary) => $summary['total_due'] == 14000.0
                && $summary['total_paid'] == 10000.0
                && $summary['total_fine'] == 500.0
                && $summary['balance'] == 4000.0);
    }

    public function test_timetable_page_shows_only_active_slots_for_students_program_and_semester(): void
    {
        $teacher = $this->createTeacher();
        $course = $this->createCourse('Programming Fundamentals', 'CS-101');
        $otherProgram = AcademicProgram::factory()->create(['name' => 'BS English']);
        $otherCourse = Course::create([
            'academic_program_id' => $otherProgram->id,
            'name' => 'Poetry',
            'code' => 'ENG-101',
            'slug' => 'poetry-eng-101',
            'course_type' => 'core',
            'semester_number' => 1,
            'credit_hours' => 3,
            'is_active' => true,
            'show_on_website' => true,
            'sort_order' => 1,
        ]);

        Timetable::create([
            'academic_program_id' => $this->program->id,
            'academic_year_id' => $this->academicYear->id,
            'course_id' => $course->id,
            'teacher_id' => $teacher->id,
            'semester' => 1,
            'semester_number' => 1,
            'day_of_week' => 'monday',
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
            'room' => 'Lab 2',
            'is_active' => true,
        ]);
        Timetable::create([
            'academic_program_id' => $this->program->id,
            'academic_year_id' => $this->academicYear->id,
            'course_id' => $course->id,
            'teacher_id' => $teacher->id,
            'semester' => 2,
            'semester_number' => 2,
            'day_of_week' => 'tuesday',
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'room' => 'Lab 3',
            'is_active' => true,
        ]);
        Timetable::create([
            'academic_program_id' => $otherProgram->id,
            'academic_year_id' => $this->academicYear->id,
            'course_id' => $otherCourse->id,
            'teacher_id' => $teacher->id,
            'semester' => 1,
            'semester_number' => 1,
            'day_of_week' => 'wednesday',
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'room' => 'R-4',
            'is_active' => true,
        ]);
        Timetable::create([
            'academic_program_id' => $this->program->id,
            'academic_year_id' => $this->academicYear->id,
            'course_id' => $course->id,
            'teacher_id' => $teacher->id,
            'semester' => 1,
            'semester_number' => 1,
            'day_of_week' => 'thursday',
            'start_time' => '11:00:00',
            'end_time' => '12:00:00',
            'room' => 'R-9',
            'is_active' => false,
        ]);

        $response = $this->asStudent()->get(route('portal.timetable'));

        $response->assertOk()
            ->assertSee('Programming Fundamentals')
            ->assertDontSee('Poetry')
            ->assertDontSee('R-9')
            ->assertViewHas('slots', fn (Collection $slots) => $slots->flatten(1)->count() === 1);
    }

    public function test_fee_challan_blocks_other_students_payment_and_allows_own_payment(): void
    {
        $ownPayment = FeePayment::factory()->create([
            'student_id' => $this->student->id,
            'challan_number' => 'CH-OWN-11',
        ]);
        $otherPayment = FeePayment::factory()->create([
            'student_id' => Student::factory()->create()->id,
            'challan_number' => 'CH-OTHER-11',
        ]);

        $this->asStudent()
            ->get(route('portal.fees.challan', $otherPayment))
            ->assertForbidden();

        $this->asStudent()
            ->get(route('portal.fees.challan', $ownPayment))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    public function test_notices_page_filters_by_audience_and_expiry(): void
    {
        Announcement::factory()->create([
            'title' => 'Visible Student Notice',
            'audience' => 'students',
            'is_published' => true,
        ]);
        Announcement::factory()->create([
            'title' => 'Visible All Notice',
            'audience' => 'all',
            'is_published' => true,
        ]);
        Announcement::factory()->create([
            'title' => 'Hidden Staff Notice',
            'audience' => 'staff',
            'is_published' => true,
        ]);
        Announcement::factory()->expired()->create([
            'title' => 'Expired Student Notice',
            'audience' => 'students',
            'is_published' => true,
        ]);

        $response = $this->asStudent()->get(route('portal.notices'));

        $response->assertOk()
            ->assertSee('Visible Student Notice')
            ->assertSee('Visible All Notice')
            ->assertDontSee('Hidden Staff Notice')
            ->assertDontSee('Expired Student Notice');
    }

    public function test_profile_page_loads_student_relationships(): void
    {
        $response = $this->asStudent()->get(route('portal.profile'));

        $response->assertOk()
            ->assertSee($this->student->name)
            ->assertSee($this->program->name)
            ->assertSee($this->department->name)
            ->assertSee($this->academicYear->name);
    }

    public function test_update_password_validates_current_password_and_confirmation(): void
    {
        $this->asStudent()
            ->post(route('portal.profile.password'), [])
            ->assertSessionHasErrors(['current_password', 'password']);

        $this->asStudent()
            ->post(route('portal.profile.password'), [
                'current_password' => 'wrong-password',
                'password' => 'new-secret',
                'password_confirmation' => 'new-secret',
            ])
            ->assertSessionHasErrors(['current_password']);

        $this->asStudent()
            ->post(route('portal.profile.password'), [
                'current_password' => 'portal@123',
                'password' => 'short',
                'password_confirmation' => 'short',
            ])
            ->assertSessionHasErrors(['password']);
    }

    public function test_update_password_accepts_default_roll_number_for_students_without_portal_password(): void
    {
        Mail::fake();

        $student = Student::factory()->create([
            'roll_number' => 'CS-2026-9090',
            'portal_password' => null,
            'email' => 'roll-number@test.com',
        ]);

        $this->actingAs($student, 'student')
            ->post(route('portal.profile.password'), [
                'current_password' => 'CS-2026-9090',
                'password' => 'newpass123',
                'password_confirmation' => 'newpass123',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $student->refresh();
        $this->assertTrue(Hash::check('newpass123', $student->portal_password));
        Mail::assertQueued(StudentPortalPasswordChangedMail::class);
    }

    public function test_update_password_rehashes_new_password_on_success(): void
    {
        Mail::fake();

        $this->asStudent()
            ->post(route('portal.profile.password'), [
                'current_password' => 'portal@123',
                'password' => 'updated-password',
                'password_confirmation' => 'updated-password',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->student->refresh();
        $this->assertTrue(Hash::check('updated-password', $this->student->portal_password));
        Mail::assertQueued(StudentPortalPasswordChangedMail::class);
    }

    private function asStudent(): static
    {
        return $this->actingAs($this->student, 'student');
    }

    private function createStudentContext(): array
    {
        $department = Department::create([
            'name' => 'Computer Science',
            'slug' => 'computer-science',
            'code' => 'CS',
            'type' => 'academic',
            'is_active' => true,
            'show_on_website' => true,
        ]);

        $academicYear = AcademicYear::create([
            'name' => '2026-2027',
            'start_date' => '2026-08-01',
            'end_date' => '2027-06-30',
            'is_current' => true,
            'is_active' => true,
        ]);

        $program = AcademicProgram::factory()->create([
            'department_id' => $department->id,
            'name' => 'BS Computer Science',
            'short_name' => 'BSCS',
        ]);

        $student = Student::factory()->create([
            'department_id' => $department->id,
            'academic_program_id' => $program->id,
            'academic_year_id' => $academicYear->id,
            'name' => 'Portal Student',
            'roll_number' => 'CS-2026-0001',
            'current_semester' => 1,
            'portal_password' => 'portal@123',
        ]);

        return [$student, $department, $academicYear, $program];
    }

    private function createCourse(string $name, string $code): Course
    {
        return Course::create([
            'department_id' => $this->department->id,
            'academic_program_id' => $this->program->id,
            'name' => $name,
            'code' => $code,
            'slug' => Str::slug($name . '-' . $code),
            'course_type' => 'core',
            'semester_number' => 1,
            'credit_hours' => 3,
            'is_active' => true,
            'show_on_website' => true,
            'sort_order' => 1,
        ]);
    }

    private function createExam(string $title, bool $resultsPublished, int $semester = 1): Exam
    {
        $course = $this->createCourse($title . ' Course', 'CRS-' . $semester . '-' . Str::upper(Str::random(3)));

        return Exam::create([
            'course_id' => $course->id,
            'academic_program_id' => $this->program->id,
            'academic_year_id' => $this->academicYear->id,
            'title' => $title,
            'exam_type' => 'midterm',
            'semester_number' => $semester,
            'exam_date' => now()->toDateString(),
            'total_marks' => 100,
            'passing_marks' => 40,
            'is_published' => true,
            'results_published' => $resultsPublished,
        ]);
    }

    private function createTeacher(): Teacher
    {
        return Teacher::create([
            'department_id' => $this->department->id,
            'employee_id' => 'EMP-' . Str::upper(Str::random(5)),
            'name' => 'Mr Teacher',
            'designation' => 'Lecturer',
            'status' => 'active',
            'is_active' => true,
        ]);
    }
}
