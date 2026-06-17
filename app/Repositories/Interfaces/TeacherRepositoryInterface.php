<?php

namespace App\Repositories\Interfaces;

use App\Models\Teacher;

interface TeacherRepositoryInterface
{
    public function all(array $filters = []);
    public function findById(int $id): ?Teacher;
    public function findByEmployeeId(string $employeeId): ?Teacher;
    public function create(array $data): Teacher;
    public function update(Teacher $teacher, array $data): Teacher;
    public function delete(Teacher $teacher): bool;
    public function nextEmployeeSequence(): int;
}
