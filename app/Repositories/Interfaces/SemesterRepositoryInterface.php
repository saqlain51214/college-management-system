<?php

namespace App\Repositories\Interfaces;

use App\Models\Semester;

interface SemesterRepositoryInterface extends RepositoryInterface
{
    public function getCurrent(): ?Semester;
    public function forYear(int $academicYearId): \Illuminate\Database\Eloquent\Collection;
}
