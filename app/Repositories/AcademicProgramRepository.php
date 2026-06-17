<?php

namespace App\Repositories;

use App\Models\AcademicProgram;
use App\Repositories\Interfaces\AcademicProgramRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AcademicProgramRepository extends BaseRepository implements AcademicProgramRepositoryInterface
{
    public function __construct(AcademicProgram $model)
    {
        parent::__construct($model);
    }

    public function allActive(): Collection
    {
        return $this->model->with('department')->active()->ordered()->get();
    }

    public function allVisible(): Collection
    {
        return $this->model->with('department')->visible()->ordered()->get();
    }

    public function allOrdered(): Collection
    {
        return $this->model->with('department')->ordered()->get();
    }

    public function forDepartment(int $departmentId): Collection
    {
        return $this->model->with('department')->forDepartment($departmentId)->ordered()->get();
    }
}
