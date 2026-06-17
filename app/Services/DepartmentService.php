<?php

namespace App\Services;

use App\Helpers\CollegeHelper;
use App\Models\Department;
use App\Repositories\DepartmentRepository;
use Illuminate\Database\Eloquent\Model;

class DepartmentService extends BaseService
{
    public function __construct(DepartmentRepository $repository)
    {
        parent::__construct($repository);
    }

    public function create(array $data): Model
    {
        $data['slug'] = CollegeHelper::generateSlug($data['name']);

        if (empty($data['code']) && ! empty($data['name'])) {
            $data['code'] = CollegeHelper::generateCode($data['name'], 'DEPT');
        }

        return $this->repository->create($data);
    }

    public function update(int $id, array $data): bool
    {
        if (isset($data['name'])) {
            $department = $this->repository->find($id);

            if ($department->name !== $data['name']) {
                $data['slug'] = CollegeHelper::generateSlug($data['name']);
            }
        }

        return $this->repository->update($id, $data);
    }

    public function updateModel(Department $department, array $data): Department
    {
        if (isset($data['name']) && $department->name !== $data['name']) {
            $data['slug'] = CollegeHelper::generateSlug($data['name']);
        }

        $department->update($data);

        return $department->refresh();
    }

    public function createModel(array $data): Department
    {
        // Slug already validated as unique by Filament form — use it as-is
        if (empty($data['slug'])) {
            $data['slug'] = CollegeHelper::generateSlug($data['name']);
        }

        if (empty($data['code']) && ! empty($data['name'])) {
            $data['code'] = CollegeHelper::generateCode($data['name'], 'DEPT');
        }

        return Department::create($data);
    }

    public function allVisible()
    {
        return $this->repository->allVisible();
    }

    public function allActive()
    {
        return $this->repository->allActive();
    }

    public function toggleStatus(int $id): bool
    {
        $department = $this->repository->find($id);

        return $this->repository->update($id, [
            'is_active' => ! $department->is_active,
        ]);
    }
}
