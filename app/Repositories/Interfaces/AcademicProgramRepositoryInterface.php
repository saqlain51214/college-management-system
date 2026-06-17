<?php

namespace App\Repositories\Interfaces;

interface AcademicProgramRepositoryInterface extends RepositoryInterface
{
    public function allActive(): \Illuminate\Database\Eloquent\Collection;
    public function allVisible(): \Illuminate\Database\Eloquent\Collection;
    public function allOrdered(): \Illuminate\Database\Eloquent\Collection;
    public function forDepartment(int $departmentId): \Illuminate\Database\Eloquent\Collection;
}
