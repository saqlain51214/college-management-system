<?php

namespace App\Console\Commands;

use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\Course;
use App\Models\Student;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class CheckAttendanceWarnings extends Command
{
    protected $signature   = 'attendance:check-warnings';
    protected $description = 'Send attendance warning notifications to students whose attendance is below 75%';

    public function handle(): int
    {
        $svc     = app(NotificationService::class);
        $warned  = 0;
        $skipped = 0;

        $students = Student::where('is_active', true)
            ->whereNotNull('academic_program_id')
            ->whereNotNull('current_semester')
            ->get();

        foreach ($students as $student) {
            $courses = Course::where('academic_program_id', $student->academic_program_id)
                ->where('semester_number', $student->current_semester)
                ->get();

            foreach ($courses as $course) {
                $totalSessions = AttendanceSession::where('course_id', $course->id)
                    ->where('academic_program_id', $student->academic_program_id)
                    ->count();

                if ($totalSessions === 0) {
                    $skipped++;
                    continue;
                }

                $present = AttendanceRecord::where('student_id', $student->id)
                    ->whereHas('session', fn($q) => $q->where('course_id', $course->id))
                    ->where('status', 'present')
                    ->count();

                $percent = ($present / $totalSessions) * 100;

                if ($percent < 75.0) {
                    $svc->send($student, 'attendance_warning', [
                        'student_name'       => $student->name,
                        'course_name'        => $course->name,
                        'attendance_percent' => number_format($percent, 1) . '%',
                        'sessions_attended'  => $present,
                        'total_sessions'     => $totalSessions,
                    ]);
                    $warned++;
                }
            }
        }

        $this->info("Sent {$warned} attendance warning(s). Skipped {$skipped} course(s) with no sessions.");
        return self::SUCCESS;
    }
}
