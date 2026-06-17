<?php

namespace App\Repositories;

use App\Models\Semester;
use App\Repositories\Interfaces\SemesterRepositoryInterface;

class SemesterRepository extends BaseRepository implements SemesterRepositoryInterface
{
    public function __construct(Semester $model)
    {
        parent::__construct($model);
    }

    public function getCurrent(): ?Semester
    {
        return $this->model->with('academicYear')->where('is_current', true)->first();
    }

    public function forYear(int $academicYearId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->where('academic_year_id', $academicYearId)
            ->orderBy('sort_order')
            ->get();
    }
}
