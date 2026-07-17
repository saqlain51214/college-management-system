<?php

namespace App\Observers;

use App\Models\Announcement;
use App\Models\Student;
use App\Models\Teacher;
use App\Services\NotificationService;

class AnnouncementNotificationObserver
{
    public function created(Announcement $announcement): void
    {
        if ($announcement->is_published) {
            $this->broadcast($announcement);
        }
    }

    public function updated(Announcement $announcement): void
    {
        if ($announcement->wasChanged('is_published') && $announcement->is_published) {
            $this->broadcast($announcement);
        }
    }

    private function broadcast(Announcement $announcement): void
    {
        $svc = app(NotificationService::class);
        $vars = ['title' => $announcement->title, 'priority' => $announcement->priority ?? 'normal'];
        $audience = $announcement->audience ?? 'all';

        if (in_array($audience, ['all', 'students', 'department'])) {
            $students = Student::where('is_active', true)
                ->when(
                    $audience === 'department' && $announcement->department_id,
                    fn($q) => $q->where('department_id', $announcement->department_id)
                )
                ->get();

            if ($students->isNotEmpty()) {
                $svc->sendToAll($students, 'new_announcement', $vars);
            }
        }

        if (in_array($audience, ['all', 'teachers', 'department'])) {
            $teachers = Teacher::where('is_active', true)
                ->when(
                    $audience === 'department' && $announcement->department_id,
                    fn($q) => $q->where('department_id', $announcement->department_id)
                )
                ->get();

            if ($teachers->isNotEmpty()) {
                $svc->sendToAll($teachers, 'teacher_new_announcement', ['title' => $announcement->title]);
            }
        }
    }
}
