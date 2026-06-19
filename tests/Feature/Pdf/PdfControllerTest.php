<?php

namespace Tests\Feature\Pdf;

use App\Models\AcademicProgram;
use App\Models\AcademicYear;
use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\Course;
use App\Models\Department;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\FeePayment;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class PdfControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Department $department;
    private AcademicYear $academicYear;
    private AcademicProgram $program;
    private Student $student;
    private Teacher $teacher;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->department = Department::create([
            'name' => 'Computer Science',
            'slug' => 'computer-science',
            'code' => 'CS',
            'type' => 'academic',
            'is_active' => true,
            'show_on_website' => true,
        ]);

        $this->academicYear = AcademicYear::create([
            'name' => '2026-2027',
            'start_date' => '2026-08-01',
            'end_date' => '2027-06-30',
            'is_current' => true,
            'is_active' => true,
        ]);

        $this->program = AcademicProgram::factory()->create([
            'department_id' => $this->department->id,
            'name' => 'BS Computer Science',
            'short_name' => 'BSCS',
        ]);

        $this->student = Student::factory()->create([
            'department_id' => $this->department->id,
            'academic_program_id' => $this->program->id,
            'academic_year_id' => $this->academicYear->id,
            'name' => 'PDF Student',
            'roll_number' => 'CS-2026-7777',
            'father_name' => 'Guardian PDF',
            'current_semester' => 1,
        ]);

        $this->teacher = Teacher::create([
            'department_id' => $this->department->id,
            'employee_id' => 'EMP-' . Str::upper(Str::random(5)),
            'name' => 'PDF Teacher',
            'designation' => 'Lecturer',
            'status' => 'active',
            'is_active' => true,
        ]);
    }

    public function test_pdf_routes_require_authenticated_web_user(): void
    {
        $payment = FeePayment::factory()->create(['student_id' => $this->student->id]);

        $this->get(route('pdf.challan', $payment))
            ->assertRedirect('/admin/login');
    }

    public function test_fee_challan_pdf_downloads_with_expected_headers(): void
    {
        $payment = FeePayment::factory()->create([
            'student_id' => $this->student->id,
            'academic_year_id' => $this->academicYear->id,
            'challan_number' => 'CH-PDF-1001',
            'amount_due' => 12000,
            'amount_paid' => 8000,
            'fine_amount' => 500,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('pdf.challan', $payment));

        $response->assertOk()
            ->assertHeader('content-type', 'application/pdf')
            ->assertHeader('content-disposition', 'attachment; filename="challan-CH-PDF-1001.pdf"');
    }

    public function test_student_transcript_pdf_downloads_with_results_and_attendance_relationships(): void
    {
        $course = $this->createCourse('Programming Fundamentals', 'CS-101');
        $exam = $this->createExam($course, 'Midterm One');

        ExamResult::create([
            'student_id' => $this->student->id,
            'exam_id' => $exam->id,
            'marks_obtained' => 84,
            'grade' => 'A',
            'grade_points' => 3.7,
            'is_absent' => false,
            'is_exempted' => false,
        ]);

        $session = AttendanceSession::create([
            'course_id' => $course->id,
            'teacher_id' => $this->teacher->id,
            'academic_program_id' => $this->program->id,
            'session_date' => now()->toDateString(),
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
            'semester_number' => 1,
            'is_locked' => false,
        ]);

        AttendanceRecord::create([
            'attendance_session_id' => $session->id,
            'student_id' => $this->student->id,
            'status' => 'present',
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('pdf.transcript', $this->student));

        $response->assertOk()
            ->assertHeader('content-type', 'application/pdf')
            ->assertHeader('content-disposition', 'attachment; filename="transcript-CS-2026-7777.pdf"');
    }

    public function test_student_attendance_pdf_downloads_with_course_wise_summary(): void
    {
        $course = $this->createCourse('Data Structures', 'CS-201');

        $sessionOne = AttendanceSession::create([
            'course_id' => $course->id,
            'teacher_id' => $this->teacher->id,
            'academic_program_id' => $this->program->id,
            'session_date' => now()->toDateString(),
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'semester_number' => 1,
            'is_locked' => false,
        ]);

        $sessionTwo = AttendanceSession::create([
            'course_id' => $course->id,
            'teacher_id' => $this->teacher->id,
            'academic_program_id' => $this->program->id,
            'session_date' => now()->subDay()->toDateString(),
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'semester_number' => 1,
            'is_locked' => false,
        ]);

        AttendanceRecord::create([
            'attendance_session_id' => $sessionOne->id,
            'student_id' => $this->student->id,
            'status' => 'present',
        ]);
        AttendanceRecord::create([
            'attendance_session_id' => $sessionTwo->id,
            'student_id' => $this->student->id,
            'status' => 'late',
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('pdf.attendance', $this->student));

        $response->assertOk()
            ->assertHeader('content-type', 'application/pdf')
            ->assertHeader('content-disposition', 'attachment; filename="attendance-CS-2026-7777.pdf"');
    }

    public function test_exam_result_sheet_pdf_downloads_with_result_statistics(): void
    {
        $course = $this->createCourse('Operating Systems', 'CS-301');
        $exam = $this->createExam($course, 'Final Exam');

        $otherStudent = Student::factory()->create([
            'academic_program_id' => $this->program->id,
            'department_id' => $this->department->id,
            'academic_year_id' => $this->academicYear->id,
            'roll_number' => 'CS-2026-8888',
        ]);

        ExamResult::create([
            'student_id' => $this->student->id,
            'exam_id' => $exam->id,
            'marks_obtained' => 91,
            'grade' => 'A',
            'grade_points' => 4.0,
            'is_absent' => false,
            'is_exempted' => false,
        ]);
        ExamResult::create([
            'student_id' => $otherStudent->id,
            'exam_id' => $exam->id,
            'marks_obtained' => null,
            'grade' => null,
            'grade_points' => null,
            'is_absent' => true,
            'is_exempted' => false,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('pdf.exam-results', $exam));

        $response->assertOk()
            ->assertHeader('content-type', 'application/pdf')
            ->assertHeader('content-disposition', 'attachment; filename="result-final-exam.pdf"');
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

    private function createExam(Course $course, string $title): Exam
    {
        return Exam::create([
            'course_id' => $course->id,
            'academic_program_id' => $this->program->id,
            'academic_year_id' => $this->academicYear->id,
            'title' => $title,
            'exam_type' => 'final',
            'semester_number' => 1,
            'exam_date' => now()->toDateString(),
            'total_marks' => 100,
            'passing_marks' => 40,
            'is_published' => true,
            'results_published' => true,
        ]);
    }
}
