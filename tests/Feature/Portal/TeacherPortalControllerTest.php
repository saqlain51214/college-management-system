<?php

namespace Tests\Feature\Portal;

use App\Models\AcademicProgram;
use App\Models\AcademicYear;
use App\Models\Announcement;
use App\Models\AttendanceSession;
use App\Models\Course;
use App\Models\LmsAssignment;
use App\Models\LmsMaterial;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Timetable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class TeacherPortalControllerTest extends TestCase
{
    use RefreshDatabase;

    private Teacher $teacher;
    private AcademicProgram $program;
    private AcademicYear $academicYear;

    protected function setUp(): void
    {
        parent::setUp();

        $this->teacher = $this->createTeacher([
            'portal_password' => 'teacher@1234',
        ]);

        $this->program = AcademicProgram::factory()->create([
            'name' => 'BS Computer Science',
        ]);

        $this->academicYear = AcademicYear::create([
            'name' => '2026-2027',
            'start_date' => '2026-08-01',
            'end_date' => '2027-06-30',
            'is_current' => true,
            'is_active' => true,
        ]);
    }

    public function test_dashboard_shows_only_teacher_scoped_stats_and_teacher_notices(): void
    {
        $course = $this->createCourse('Programming Fundamentals', 'CS-101');
        $otherTeacher = $this->createTeacher([
            'employee_id' => 'JDCA-T-099',
            'email' => 'other-teacher@jdca.edu.pk',
        ]);

        Timetable::create([
            'academic_program_id' => $this->program->id,
            'academic_year_id' => $this->academicYear->id,
            'course_id' => $course->id,
            'teacher_id' => $this->teacher->id,
            'semester' => 1,
            'semester_number' => 1,
            'day_of_week' => strtolower(now()->format('l')),
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
            'room' => 'Lab 1',
            'is_active' => true,
        ]);
        Timetable::create([
            'academic_program_id' => $this->program->id,
            'academic_year_id' => $this->academicYear->id,
            'course_id' => $course->id,
            'teacher_id' => $otherTeacher->id,
            'semester' => 1,
            'semester_number' => 1,
            'day_of_week' => strtolower(now()->format('l')),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'room' => 'Lab 2',
            'is_active' => true,
        ]);

        LmsMaterial::create([
            'course_id' => $course->id,
            'teacher_id' => $this->teacher->id,
            'title' => 'Week 1 Slides',
            'material_type' => 'notes',
            'is_published' => true,
        ]);
        LmsMaterial::create([
            'course_id' => $course->id,
            'teacher_id' => $otherTeacher->id,
            'title' => 'Other Teacher Material',
            'material_type' => 'notes',
            'is_published' => true,
        ]);

        LmsAssignment::create([
            'course_id' => $course->id,
            'teacher_id' => $this->teacher->id,
            'title' => 'Assignment 1',
            'total_marks' => 20,
            'due_datetime' => now()->addDays(3),
            'submission_type' => 'file_upload',
            'is_published' => true,
        ]);

        AttendanceSession::create([
            'course_id' => $course->id,
            'teacher_id' => $this->teacher->id,
            'academic_program_id' => $this->program->id,
            'session_date' => now()->toDateString(),
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
            'semester_number' => 1,
            'is_locked' => false,
        ]);

        Announcement::factory()->create([
            'title' => 'Teacher Notice',
            'audience' => 'teachers',
            'is_published' => true,
        ]);
        Announcement::factory()->create([
            'title' => 'All Notice',
            'audience' => 'all',
            'is_published' => true,
        ]);
        Announcement::factory()->create([
            'title' => 'Student Notice',
            'audience' => 'students',
            'is_published' => true,
        ]);

        $response = $this->asTeacher()->get(route('teacher.dashboard'));

        $response->assertOk()
            ->assertViewHas('stats', fn (array $stats) => $stats['active_classes'] === 1
                && $stats['today_classes'] === 1
                && $stats['materials'] === 1
                && $stats['assignments'] === 1
                && $stats['attendance_sessions'] === 1)
            ->assertViewHas('todaySchedule', fn (Collection $schedule) => $schedule->count() === 1
                && $schedule->first()->room === 'Lab 1')
            ->assertViewHas('notices', fn (Collection $notices) => $notices->pluck('title')->contains('Teacher Notice')
                && $notices->pluck('title')->contains('All Notice')
                && ! $notices->pluck('title')->contains('Student Notice'));
    }

    public function test_timetable_page_shows_only_active_slots_for_authenticated_teacher(): void
    {
        $course = $this->createCourse('Data Structures', 'CS-201');
        $otherTeacher = $this->createTeacher([
            'employee_id' => 'JDCA-T-100',
            'email' => 'other2@jdca.edu.pk',
        ]);

        Timetable::create([
            'academic_program_id' => $this->program->id,
            'academic_year_id' => $this->academicYear->id,
            'course_id' => $course->id,
            'teacher_id' => $this->teacher->id,
            'semester' => 3,
            'semester_number' => 3,
            'day_of_week' => 'monday',
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'room' => 'R-3',
            'is_active' => true,
        ]);
        Timetable::create([
            'academic_program_id' => $this->program->id,
            'academic_year_id' => $this->academicYear->id,
            'course_id' => $course->id,
            'teacher_id' => $this->teacher->id,
            'semester' => 3,
            'semester_number' => 3,
            'day_of_week' => 'tuesday',
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'room' => 'R-4',
            'is_active' => false,
        ]);
        Timetable::create([
            'academic_program_id' => $this->program->id,
            'academic_year_id' => $this->academicYear->id,
            'course_id' => $course->id,
            'teacher_id' => $otherTeacher->id,
            'semester' => 3,
            'semester_number' => 3,
            'day_of_week' => 'wednesday',
            'start_time' => '11:00:00',
            'end_time' => '12:00:00',
            'room' => 'R-5',
            'is_active' => true,
        ]);

        $response = $this->asTeacher()->get(route('teacher.timetable'));

        $response->assertOk()
            ->assertSee('R-3')
            ->assertDontSee('R-4')
            ->assertDontSee('R-5');
    }

    public function test_materials_and_assignments_pages_are_limited_to_authenticated_teacher(): void
    {
        $course = $this->createCourse('Database Systems', 'CS-301');
        $otherTeacher = $this->createTeacher([
            'employee_id' => 'JDCA-T-111',
            'email' => 'other3@jdca.edu.pk',
        ]);

        LmsMaterial::create([
            'course_id' => $course->id,
            'teacher_id' => $this->teacher->id,
            'title' => 'ER Diagram Notes',
            'material_type' => 'notes',
            'is_published' => true,
        ]);
        LmsMaterial::create([
            'course_id' => $course->id,
            'teacher_id' => $otherTeacher->id,
            'title' => 'Other Material',
            'material_type' => 'notes',
            'is_published' => true,
        ]);

        LmsAssignment::create([
            'course_id' => $course->id,
            'teacher_id' => $this->teacher->id,
            'title' => 'Normalization Task',
            'total_marks' => 15,
            'due_datetime' => now()->addDays(2),
            'submission_type' => 'file_upload',
            'is_published' => true,
        ]);
        LmsAssignment::create([
            'course_id' => $course->id,
            'teacher_id' => $otherTeacher->id,
            'title' => 'Other Assignment',
            'total_marks' => 10,
            'due_datetime' => now()->addDays(1),
            'submission_type' => 'file_upload',
            'is_published' => true,
        ]);

        $this->asTeacher()->get(route('teacher.materials'))
            ->assertOk()
            ->assertSee('ER Diagram Notes')
            ->assertDontSee('Other Material');

        $this->asTeacher()->get(route('teacher.assignments'))
            ->assertOk()
            ->assertSee('Normalization Task')
            ->assertDontSee('Other Assignment');
    }

    public function test_profile_password_update_validates_current_password_and_hashes_new_password(): void
    {
        $this->asTeacher()
            ->post(route('teacher.profile.password'), [
                'current_password' => 'teacher@1234',
                'password' => 'updated-password',
                'password_confirmation' => 'updated-password',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->teacher->refresh();

        $this->assertTrue(Hash::check('updated-password', $this->teacher->portal_password));
    }

    public function test_teacher_can_create_material_assignment_and_attendance_for_assigned_course(): void
    {
        $course = $this->createCourse('Operating Systems', 'CS-401');

        Timetable::create([
            'academic_program_id' => $this->program->id,
            'academic_year_id' => $this->academicYear->id,
            'course_id' => $course->id,
            'teacher_id' => $this->teacher->id,
            'semester' => 4,
            'semester_number' => 4,
            'day_of_week' => 'monday',
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'room' => 'Lab 4',
            'is_active' => true,
        ]);

        $this->asTeacher()
            ->post(route('teacher.materials.store'), [
                'title' => 'Kernel Notes',
                'course_id' => $course->id,
                'material_type' => 'document',
                'description' => 'Week 1 discussion notes',
                'week_number' => 1,
                'is_published' => '1',
            ])
            ->assertRedirect(route('teacher.materials'));

        $this->assertDatabaseHas('lms_materials', [
            'teacher_id' => $this->teacher->id,
            'course_id' => $course->id,
            'title' => 'Kernel Notes',
        ]);

        $this->asTeacher()
            ->post(route('teacher.assignments.store'), [
                'title' => 'Process Scheduling Task',
                'course_id' => $course->id,
                'total_marks' => 20,
                'submission_type' => 'file',
                'is_published' => '1',
            ])
            ->assertRedirect(route('teacher.assignments'));

        $this->assertDatabaseHas('lms_assignments', [
            'teacher_id' => $this->teacher->id,
            'course_id' => $course->id,
            'title' => 'Process Scheduling Task',
        ]);

        $this->asTeacher()
            ->post(route('teacher.attendance.store'), [
                'course_id' => $course->id,
                'session_date' => now()->toDateString(),
                'start_time' => '09:00',
                'end_time' => '10:00',
                'topic_covered' => 'Processes and threads',
            ])
            ->assertRedirect(route('teacher.attendance'));

        $this->assertDatabaseHas('attendance_sessions', [
            'teacher_id' => $this->teacher->id,
            'course_id' => $course->id,
            'topic_covered' => 'Processes and threads',
        ]);
    }

    public function test_teacher_cannot_edit_another_teachers_material(): void
    {
        $course = $this->createCourse('Computer Networks', 'CS-402');
        $otherTeacher = $this->createTeacher([
            'employee_id' => 'JDCA-T-222',
            'email' => 'other-edit@jdca.edu.pk',
        ]);

        $material = LmsMaterial::create([
            'course_id' => $course->id,
            'teacher_id' => $otherTeacher->id,
            'title' => 'Routing Notes',
            'material_type' => 'document',
            'is_published' => true,
        ]);

        $this->asTeacher()
            ->get(route('teacher.materials.edit', $material))
            ->assertForbidden();
    }

    public function test_teacher_can_mark_attendance_for_students_in_owned_session(): void
    {
        $course = $this->createCourse('Compiler Construction', 'CS-403');

        $session = \App\Models\AttendanceSession::create([
            'teacher_id' => $this->teacher->id,
            'course_id' => $course->id,
            'academic_program_id' => $this->program->id,
            'session_date' => now()->toDateString(),
            'semester_number' => 1,
            'section' => 'A',
            'topic_covered' => 'Lexical analysis',
            'is_locked' => false,
        ]);

        $studentOne = Student::factory()->create([
            'academic_program_id' => $this->program->id,
            'current_semester' => 1,
            'section' => 'A',
            'is_active' => true,
            'status' => 'active',
        ]);
        $studentTwo = Student::factory()->create([
            'academic_program_id' => $this->program->id,
            'current_semester' => 1,
            'section' => 'A',
            'is_active' => true,
            'status' => 'active',
        ]);
        Student::factory()->create([
            'academic_program_id' => $this->program->id,
            'current_semester' => 2,
            'section' => 'A',
            'is_active' => true,
            'status' => 'active',
        ]);

        $this->asTeacher()
            ->post(route('teacher.attendance.save', $session), [
                'attendance' => [
                    $studentOne->id => ['status' => 'present', 'remarks' => 'On time'],
                    $studentTwo->id => ['status' => 'late', 'remarks' => 'Arrived 10 minutes late'],
                ],
            ])
            ->assertRedirect(route('teacher.attendance.mark', $session))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('attendance_records', [
            'attendance_session_id' => $session->id,
            'student_id' => $studentOne->id,
            'status' => 'present',
            'remarks' => 'On time',
        ]);
        $this->assertDatabaseHas('attendance_records', [
            'attendance_session_id' => $session->id,
            'student_id' => $studentTwo->id,
            'status' => 'late',
            'remarks' => 'Arrived 10 minutes late',
        ]);
    }

    public function test_teacher_cannot_mark_attendance_for_another_teachers_session(): void
    {
        $course = $this->createCourse('Artificial Intelligence', 'CS-404');
        $otherTeacher = $this->createTeacher([
            'employee_id' => 'JDCA-T-333',
            'email' => 'other-session@jdca.edu.pk',
        ]);

        $session = \App\Models\AttendanceSession::create([
            'teacher_id' => $otherTeacher->id,
            'course_id' => $course->id,
            'academic_program_id' => $this->program->id,
            'session_date' => now()->toDateString(),
            'semester_number' => 1,
            'is_locked' => false,
        ]);

        $this->asTeacher()
            ->get(route('teacher.attendance.mark', $session))
            ->assertForbidden();
    }

    private function asTeacher()
    {
        return $this->actingAs($this->teacher, 'teacher');
    }

    private function createTeacher(array $attributes = []): Teacher
    {
        return Teacher::create(array_merge([
            'employee_id' => 'JDCA-T-001',
            'name' => 'Muhammad Bilal',
            'email' => 'teacher@jdca.edu.pk',
            'designation' => 'Lecturer',
            'status' => 'active',
            'is_active' => true,
        ], $attributes));
    }

    private function createCourse(string $name, string $code): Course
    {
        return Course::create([
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
}
