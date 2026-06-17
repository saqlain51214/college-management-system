<?php

namespace App\Repositories\Interfaces;

use App\Models\AcademicYear;

interface AcademicYearRepositoryInterface extends RepositoryInterface
{
    public function getCurrent(): ?AcademicYear;
    public function allActive(): \Illuminate\Database\Eloquent\Collection;
}
