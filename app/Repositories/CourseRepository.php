<?php

namespace App\Repositories;

use App\Models\Course;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CourseRepository extends BaseRepository implements CourseRepositoryInterface
{
    public function __construct(Course $model)
    {
        parent::__construct($model);
    }

    public function allActive(): Collection
    {
        return $this->model->with(['department', 'academicProgram'])->active()->ordered()->get();
    }

    public function forProgram(int $programId): Collection
    {
        return $this->model->with('department')->forProgram($programId)->ordered()->get();
    }

    public function forDepartment(int $deptId): Collection
    {
        return $this->model->with('academicProgram')->forDepartment($deptId)->ordered()->get();
    }
}
