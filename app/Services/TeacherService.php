<?php

namespace App\Services;

use App\Mail\TeacherWelcomeMail;
use App\Models\Teacher;
use App\Repositories\Interfaces\TeacherRepositoryInterface;
use Illuminate\Support\Facades\Mail;

class TeacherService
{
    public function __construct(private TeacherRepositoryInterface $repo) {}

    public function createTeacher(array $data): Teacher
    {
        if (empty($data['employee_id'])) {
            $seq = $this->repo->nextEmployeeSequence();
            $data['employee_id'] = sprintf('EMP-%04d', $seq);
        }

        $teacher = $this->repo->create($data);

        if (filled($teacher->email) && config('platform.notifications.send_teacher_welcome_email', true)) {
            Mail::to($teacher->email)->queue(new TeacherWelcomeMail($teacher));
        }

        return $teacher;
    }

    public function updateTeacher(Teacher $teacher, array $data): Teacher
    {
        return $this->repo->update($teacher, $data);
    }

    public function deleteTeacher(Teacher $teacher): bool
    {
        return $this->repo->delete($teacher);
    }
}
