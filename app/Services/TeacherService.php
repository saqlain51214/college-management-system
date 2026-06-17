<?php

namespace App\Services;

use App\Models\Teacher;
use App\Repositories\Interfaces\TeacherRepositoryInterface;

class TeacherService
{
    public function __construct(private TeacherRepositoryInterface $repo) {}

    public function createTeacher(array $data): Teacher
    {
        if (empty($data['employee_id'])) {
            $seq = $this->repo->nextEmployeeSequence();
            $data['employee_id'] = sprintf('EMP-%04d', $seq);
        }

        return $this->repo->create($data);
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
