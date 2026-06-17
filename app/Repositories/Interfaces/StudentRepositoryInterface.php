<?php

namespace App\Repositories\Interfaces;

use App\Models\Student;

interface StudentRepositoryInterface
{
    public function all(array $filters = []);
    public function findById(int $id): ?Student;
    public function findByRollNumber(string $rollNumber): ?Student;
    public function create(array $data): Student;
    public function update(Student $student, array $data): Student;
    public function delete(Student $student): bool;
    public function nextSequence(string $deptCode, int $batchYear): int;
}
