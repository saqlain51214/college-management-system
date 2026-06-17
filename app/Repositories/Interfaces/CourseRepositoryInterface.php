<?php

namespace App\Repositories\Interfaces;

interface CourseRepositoryInterface extends RepositoryInterface
{
    public function allActive(): \Illuminate\Database\Eloquent\Collection;
    public function forProgram(int $programId): \Illuminate\Database\Eloquent\Collection;
    public function forDepartment(int $deptId): \Illuminate\Database\Eloquent\Collection;
}
