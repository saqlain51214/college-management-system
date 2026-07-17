<?php

namespace App\Console\Commands;

use App\Models\AttendanceSession;
use App\Models\Timetable;
use App\Services\NotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class AttendanceReminder extends Command
{
    protected $signature   = 'attendance:send-reminders';
    protected $description = 'Remind teachers who have classes today but have not marked attendance';

    public function handle(): int
    {
        $svc     = app(NotificationService::class);
        $today   = Carbon::today();
        $dayName = strtolower($today->englishDayOfWeek); // monday, tuesday, ...
        $sent    = 0;

        // Find timetable entries for today
        $slots = Timetable::with(['teacher', 'course'])
            ->where('day_of_week', $dayName)
            ->where('is_active', true)
            ->whereNotNull('teacher_id')
            ->get();

        foreach ($slots as $slot) {
            if (!$slot->teacher || !$slot->course) continue;

            // Check if this teacher already created an AttendanceSession today for this course
            $alreadyMarked = AttendanceSession::where('teacher_id', $slot->teacher_id)
                ->where('course_id', $slot->course_id)
                ->whereDate('session_date', $today)
                ->exists();

            if (!$alreadyMarked) {
                $svc->send($slot->teacher, 'attendance_reminder', [
                    'teacher_name' => $slot->teacher->name,
                    'course_name'  => $slot->course->name,
                    'session_date' => $today->format('d M Y'),
                ]);
                $sent++;
            }
        }

        $this->info("Sent {$sent} attendance reminder(s) to teachers.");
        return self::SUCCESS;
    }
}
