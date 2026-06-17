<?php

namespace App\Repositories;

use App\Models\Teacher;
use App\Repositories\Interfaces\TeacherRepositoryInterface;

class TeacherRepository implements TeacherRepositoryInterface
{
    public function all(array $filters = [])
    {
        return Teacher::with('department')
            ->when($filters['department_id'] ?? null, fn($q, $id) => $q->where('department_id', $id))
            ->when($filters['status'] ?? null, fn($q, $s) => $q->where('status', $s))
            ->when($filters['employment_type'] ?? null, fn($q, $t) => $q->where('employment_type', $t))
            ->orderBy('name')
            ->get();
    }

    public function findById(int $id): ?Teacher
    {
        return Teacher::with('department')->find($id);
    }

    public function findByEmployeeId(string $employeeId): ?Teacher
    {
        return Teacher::where('employee_id', $employeeId)->first();
    }

    public function create(array $data): Teacher
    {
        return Teacher::create($data);
    }

    public function update(Teacher $teacher, array $data): Teacher
    {
        $teacher->update($data);
        return $teacher->fresh();
    }

    public function delete(Teacher $teacher): bool
    {
        return $teacher->delete();
    }

    public function nextEmployeeSequence(): int
    {
        $last = Teacher::withTrashed()
            ->where('employee_id', 'like', 'EMP-%')
            ->orderByDesc('employee_id')
            ->value('employee_id');

        if (! $last) {
            return 1;
        }

        $parts = explode('-', $last);
        return ((int) end($parts)) + 1;
    }
}
