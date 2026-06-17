<?php

namespace App\Repositories;

use App\Models\Student;
use App\Repositories\Interfaces\StudentRepositoryInterface;

class StudentRepository implements StudentRepositoryInterface
{
    public function all(array $filters = [])
    {
        return Student::with(['department', 'academicProgram', 'academicYear'])
            ->when($filters['department_id'] ?? null, fn($q, $id) => $q->where('department_id', $id))
            ->when($filters['academic_program_id'] ?? null, fn($q, $id) => $q->where('academic_program_id', $id))
            ->when($filters['status'] ?? null, fn($q, $s) => $q->where('status', $s))
            ->when($filters['batch_year'] ?? null, fn($q, $y) => $q->where('batch_year', $y))
            ->orderBy('roll_number')
            ->get();
    }

    public function findById(int $id): ?Student
    {
        return Student::with(['department', 'academicProgram', 'academicYear'])->find($id);
    }

    public function findByRollNumber(string $rollNumber): ?Student
    {
        return Student::where('roll_number', $rollNumber)->first();
    }

    public function create(array $data): Student
    {
        return Student::create($data);
    }

    public function update(Student $student, array $data): Student
    {
        $student->update($data);
        return $student->fresh();
    }

    public function delete(Student $student): bool
    {
        return $student->delete();
    }

    public function nextSequence(string $deptCode, int $batchYear): int
    {
        $pattern = strtoupper($deptCode) . '-' . $batchYear . '-%';

        $last = Student::withTrashed()
            ->where('roll_number', 'like', $pattern)
            ->orderByDesc('roll_number')
            ->value('roll_number');

        if (! $last) {
            return 1;
        }

        $parts = explode('-', $last);
        return ((int) end($parts)) + 1;
    }
}
