<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\AttendanceSession;
use App\Models\Course;
use App\Models\LmsAssignment;
use App\Models\LmsMaterial;
use App\Models\ListItem;
use App\Models\Teacher;
use App\Models\Timetable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TeacherPortalController extends Controller
{
    private function teacher(): Teacher
    {
        /** @var Teacher $teacher */
        $teacher = Auth::guard('teacher')->user();

        return $teacher->load('department');
    }

    public function dashboard()
    {
        $teacher = $this->teacher();
        $today = now()->format('l');
        $todayKey = strtolower($today);

        $stats = [
            'active_classes' => Timetable::where('teacher_id', $teacher->id)->where('is_active', true)->count(),
            'today_classes' => Timetable::where('teacher_id', $teacher->id)->where('is_active', true)->where('day_of_week', $todayKey)->count(),
            'materials' => LmsMaterial::where('teacher_id', $teacher->id)->count(),
            'assignments' => LmsAssignment::where('teacher_id', $teacher->id)->count(),
            'attendance_sessions' => AttendanceSession::where('teacher_id', $teacher->id)->count(),
        ];

        $todaySchedule = Timetable::with(['course', 'academicProgram'])
            ->where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->where('day_of_week', $todayKey)
            ->orderBy('start_time')
            ->get();

        $recentMaterials = LmsMaterial::with('course')
            ->where('teacher_id', $teacher->id)
            ->latest()
            ->limit(5)
            ->get();

        $upcomingAssignments = LmsAssignment::with('course')
            ->where('teacher_id', $teacher->id)
            ->orderBy('due_datetime')
            ->limit(5)
            ->get();

        $notices = $this->teacherAnnouncements()
            ->orderByDesc('publish_date')
            ->limit(5)
            ->get();

        return view('teacher.dashboard', compact(
            'teacher',
            'stats',
            'today',
            'todaySchedule',
            'recentMaterials',
            'upcomingAssignments',
            'notices',
        ));
    }

    public function timetable()
    {
        $teacher = $this->teacher();
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

        $slots = Timetable::with(['course', 'academicProgram'])
            ->where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->orderByRaw("CASE day_of_week WHEN 'monday' THEN 1 WHEN 'tuesday' THEN 2 WHEN 'wednesday' THEN 3 WHEN 'thursday' THEN 4 WHEN 'friday' THEN 5 WHEN 'saturday' THEN 6 ELSE 7 END")
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_of_week');

        return view('teacher.timetable', compact('teacher', 'slots', 'days'));
    }

    public function materials()
    {
        $teacher = $this->teacher();

        $materials = LmsMaterial::with('course')
            ->where('teacher_id', $teacher->id)
            ->latest()
            ->paginate(12);

        return view('teacher.materials', compact('teacher', 'materials'));
    }

    public function createMaterial()
    {
        $teacher = $this->teacher();
        $courses = $this->availableCourses($teacher);

        return view('teacher.materials-create', [
            'teacher' => $teacher,
            'courses' => $courses,
            'materialTypes' => $this->materialTypeOptions(),
        ]);
    }

    public function storeMaterial(Request $request)
    {
        $teacher = $this->teacher();
        $allowedCourseIds = $this->availableCourses($teacher)->pluck('id')->all();

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'course_id' => ['required', 'integer', Rule::in($allowedCourseIds)],
            'material_type' => ['required', 'string', Rule::in(array_keys($this->materialTypeOptions()))],
            'description' => ['nullable', 'string'],
            'external_url' => ['nullable', 'url', 'max:500'],
            'week_number' => ['nullable', 'integer', 'min:1', 'max:18'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        LmsMaterial::create([
            'teacher_id' => $teacher->id,
            'course_id' => $validated['course_id'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'material_type' => $validated['material_type'],
            'external_url' => $validated['external_url'] ?? null,
            'week_number' => $validated['week_number'] ?? null,
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect()->route('teacher.materials')->with('success', 'Material created successfully.');
    }

    public function editMaterial(LmsMaterial $material)
    {
        $teacher = $this->teacher();
        $this->abortIfNotOwnedByTeacher($material->teacher_id, $teacher->id);

        return view('teacher.materials-edit', [
            'teacher' => $teacher,
            'material' => $material,
            'courses' => $this->availableCourses($teacher),
            'materialTypes' => $this->materialTypeOptions(),
        ]);
    }

    public function updateMaterial(Request $request, LmsMaterial $material)
    {
        $teacher = $this->teacher();
        $this->abortIfNotOwnedByTeacher($material->teacher_id, $teacher->id);

        $allowedCourseIds = $this->availableCourses($teacher)->pluck('id')->all();

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'course_id' => ['required', 'integer', Rule::in($allowedCourseIds)],
            'material_type' => ['required', 'string', Rule::in(array_keys($this->materialTypeOptions()))],
            'description' => ['nullable', 'string'],
            'external_url' => ['nullable', 'url', 'max:500'],
            'week_number' => ['nullable', 'integer', 'min:1', 'max:18'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $material->update([
            'course_id' => $validated['course_id'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'material_type' => $validated['material_type'],
            'external_url' => $validated['external_url'] ?? null,
            'week_number' => $validated['week_number'] ?? null,
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect()->route('teacher.materials')->with('success', 'Material updated successfully.');
    }

    public function assignments()
    {
        $teacher = $this->teacher();

        $assignments = LmsAssignment::with('course')
            ->where('teacher_id', $teacher->id)
            ->orderBy('due_datetime')
            ->paginate(12);

        return view('teacher.assignments', compact('teacher', 'assignments'));
    }

    public function createAssignment()
    {
        $teacher = $this->teacher();

        return view('teacher.assignments-create', [
            'teacher' => $teacher,
            'courses' => $this->availableCourses($teacher),
            'submissionTypes' => $this->submissionTypeOptions(),
        ]);
    }

    public function storeAssignment(Request $request)
    {
        $teacher = $this->teacher();
        $allowedCourseIds = $this->availableCourses($teacher)->pluck('id')->all();

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'course_id' => ['required', 'integer', Rule::in($allowedCourseIds)],
            'description' => ['nullable', 'string'],
            'instructions' => ['nullable', 'string'],
            'total_marks' => ['required', 'numeric', 'min:1', 'max:1000'],
            'due_datetime' => ['nullable', 'date'],
            'submission_type' => ['required', 'string', Rule::in(array_keys($this->submissionTypeOptions()))],
            'allow_late_submission' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        LmsAssignment::create([
            'teacher_id' => $teacher->id,
            'course_id' => $validated['course_id'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'instructions' => $validated['instructions'] ?? null,
            'total_marks' => $validated['total_marks'],
            'due_datetime' => $validated['due_datetime'] ?? null,
            'submission_type' => $validated['submission_type'],
            'allow_late_submission' => $request->boolean('allow_late_submission'),
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect()->route('teacher.assignments')->with('success', 'Assignment created successfully.');
    }

    public function editAssignment(LmsAssignment $assignment)
    {
        $teacher = $this->teacher();
        $this->abortIfNotOwnedByTeacher($assignment->teacher_id, $teacher->id);

        return view('teacher.assignments-edit', [
            'teacher' => $teacher,
            'assignment' => $assignment,
            'courses' => $this->availableCourses($teacher),
            'submissionTypes' => $this->submissionTypeOptions(),
        ]);
    }

    public function updateAssignment(Request $request, LmsAssignment $assignment)
    {
        $teacher = $this->teacher();
        $this->abortIfNotOwnedByTeacher($assignment->teacher_id, $teacher->id);

        $allowedCourseIds = $this->availableCourses($teacher)->pluck('id')->all();

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'course_id' => ['required', 'integer', Rule::in($allowedCourseIds)],
            'description' => ['nullable', 'string'],
            'instructions' => ['nullable', 'string'],
            'total_marks' => ['required', 'numeric', 'min:1', 'max:1000'],
            'due_datetime' => ['nullable', 'date'],
            'submission_type' => ['required', 'string', Rule::in(array_keys($this->submissionTypeOptions()))],
            'allow_late_submission' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $assignment->update([
            'course_id' => $validated['course_id'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'instructions' => $validated['instructions'] ?? null,
            'total_marks' => $validated['total_marks'],
            'due_datetime' => $validated['due_datetime'] ?? null,
            'submission_type' => $validated['submission_type'],
            'allow_late_submission' => $request->boolean('allow_late_submission'),
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect()->route('teacher.assignments')->with('success', 'Assignment updated successfully.');
    }

    public function attendance()
    {
        $teacher = $this->teacher();

        $sessions = AttendanceSession::with(['course', 'academicProgram'])
            ->where('teacher_id', $teacher->id)
            ->latest('session_date')
            ->paginate(12);

        return view('teacher.attendance', compact('teacher', 'sessions'));
    }

    public function createAttendance()
    {
        $teacher = $this->teacher();

        return view('teacher.attendance-create', [
            'teacher' => $teacher,
            'courses' => $this->availableCourses($teacher),
        ]);
    }

    public function storeAttendance(Request $request)
    {
        $teacher = $this->teacher();
        $courses = $this->availableCourses($teacher);
        $allowedCourseIds = $courses->pluck('id')->all();

        $validated = $request->validate([
            'course_id' => ['required', 'integer', Rule::in($allowedCourseIds)],
            'session_date' => ['required', 'date', 'before_or_equal:today'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i', 'after:start_time'],
            'section' => ['nullable', 'string', 'max:10'],
            'topic_covered' => ['nullable', 'string', 'max:200'],
            'remarks' => ['nullable', 'string'],
        ]);

        $course = $courses->firstWhere('id', (int) $validated['course_id']);

        AttendanceSession::create([
            'teacher_id' => $teacher->id,
            'course_id' => $course->id,
            'academic_program_id' => $course->academic_program_id,
            'semester_number' => $course->semester_number,
            'session_date' => $validated['session_date'],
            'start_time' => $validated['start_time'] ?? null,
            'end_time' => $validated['end_time'] ?? null,
            'section' => $validated['section'] ?? null,
            'topic_covered' => $validated['topic_covered'] ?? null,
            'remarks' => $validated['remarks'] ?? null,
            'is_locked' => false,
        ]);

        return redirect()->route('teacher.attendance')->with('success', 'Attendance session created successfully.');
    }

    public function markAttendance(AttendanceSession $session)
    {
        $teacher = $this->teacher();
        $this->abortIfNotOwnedByTeacher($session->teacher_id, $teacher->id);

        $students = $this->studentsForSession($session);
        $existingRecords = $session->records()
            ->get()
            ->keyBy('student_id');

        return view('teacher.attendance-mark', compact('teacher', 'session', 'students', 'existingRecords'));
    }

    public function saveAttendance(Request $request, AttendanceSession $session)
    {
        $teacher = $this->teacher();
        $this->abortIfNotOwnedByTeacher($session->teacher_id, $teacher->id);

        $students = $this->studentsForSession($session);
        $studentIds = $students->pluck('id')->all();

        $validated = $request->validate([
            'attendance' => ['required', 'array'],
            'attendance.*.status' => ['required', Rule::in(['present', 'absent', 'late'])],
            'attendance.*.remarks' => ['nullable', 'string'],
        ]);

        foreach ($studentIds as $studentId) {
            $recordData = $validated['attendance'][$studentId] ?? null;

            if (! $recordData) {
                continue;
            }

            $session->records()->updateOrCreate(
                ['student_id' => $studentId],
                [
                    'status' => $recordData['status'],
                    'remarks' => $recordData['remarks'] ?? null,
                ]
            );
        }

        return redirect()->route('teacher.attendance.mark', $session)->with('success', 'Attendance saved successfully.');
    }

    public function notices()
    {
        $teacher = $this->teacher();

        $notices = $this->teacherAnnouncements()
            ->orderByDesc('publish_date')
            ->paginate(15);

        return view('teacher.notices', compact('teacher', 'notices'));
    }

    public function profile()
    {
        $teacher = $this->teacher();

        return view('teacher.profile', compact('teacher'));
    }

    public function updatePassword(Request $request)
    {
        $teacher = $this->teacher();

        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $valid = $teacher->portal_password
            ? Hash::check($request->current_password, $teacher->portal_password)
            : ($request->current_password === $teacher->employee_id);

        if (! $valid) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
        }

        $teacher->update(['portal_password' => $request->password]);

        return back()->with('success', 'Password updated successfully.');
    }

    private function teacherAnnouncements()
    {
        return Announcement::query()
            ->where('is_published', true)
            ->where(fn ($query) => $query->whereNull('expiry_date')->orWhere('expiry_date', '>=', now()))
            ->where(fn ($query) => $query->where('audience', 'all')->orWhere('audience', 'teachers')->orWhereNull('audience'));
    }

    private function availableCourses(Teacher $teacher)
    {
        $courseIds = Timetable::where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->pluck('course_id')
            ->merge(LmsMaterial::where('teacher_id', $teacher->id)->pluck('course_id'))
            ->merge(LmsAssignment::where('teacher_id', $teacher->id)->pluck('course_id'))
            ->filter()
            ->unique()
            ->values();

        if ($courseIds->isEmpty()) {
            return collect();
        }

        return Course::with('academicProgram')
            ->whereIn('id', $courseIds)
            ->active()
            ->ordered()
            ->get();
    }

    private function studentsForSession(AttendanceSession $session)
    {
        return \App\Models\Student::query()
            ->active()
            ->where('academic_program_id', $session->academic_program_id)
            ->where('current_semester', $session->semester_number)
            ->when(filled($session->section), fn ($query) => $query->where('section', $session->section))
            ->orderBy('name')
            ->get();
    }

    private function materialTypeOptions(): array
    {
        return ListItem::getOptions('lms_material_type', [
            'document' => 'Document',
            'notes' => 'Notes',
            'video' => 'Video',
            'link' => 'Link',
        ]);
    }

    private function submissionTypeOptions(): array
    {
        return ListItem::getOptions('lms_submission_type', [
            'file' => 'File Upload',
            'text' => 'Text Submission',
            'link' => 'External Link',
        ]);
    }

    private function abortIfNotOwnedByTeacher(?int $recordTeacherId, int $teacherId): void
    {
        abort_if($recordTeacherId !== $teacherId, 403);
    }
}
