<?php

namespace App\Repositories;

use App\Models\AcademicYear;
use App\Repositories\Interfaces\AcademicYearRepositoryInterface;

class AcademicYearRepository extends BaseRepository implements AcademicYearRepositoryInterface
{
    public function __construct(AcademicYear $model)
    {
        parent::__construct($model);
    }

    public function getCurrent(): ?AcademicYear
    {
        return $this->model->where('is_current', true)->first();
    }

    public function allActive(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->active()->orderByDesc('start_date')->get();
    }
}
