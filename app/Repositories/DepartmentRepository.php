<?php

namespace App\Repositories;

use App\Models\Department;
use App\Repositories\Interfaces\DepartmentRepositoryInterface;

class DepartmentRepository extends BaseRepository implements DepartmentRepositoryInterface
{
    public function __construct(Department $model)
    {
        parent::__construct($model);
    }

    public function allActive()
    {
        return $this->model->active()->ordered()->get();
    }

    public function allVisible()
    {
        return $this->model->visible()->ordered()->get();
    }

    public function allOrdered()
    {
        return $this->model->ordered()->get();
    }
}
