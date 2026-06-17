<?php

namespace App\Services;

use App\Helpers\CollegeHelper;
use App\Models\AcademicProgram;
use App\Repositories\AcademicProgramRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class AcademicProgramService extends BaseService
{
    public function __construct(AcademicProgramRepository $repository)
    {
        parent::__construct($repository);
    }

    public function createModel(array $data): AcademicProgram
    {
        if (empty($data['slug'])) {
            $data['slug'] = CollegeHelper::generateSlug($data['name']);
        }

        if (empty($data['code']) && ! empty($data['short_name'])) {
            $data['code'] = CollegeHelper::generateCode($data['short_name']);
        }

        return AcademicProgram::create($data);
    }

    public function updateModel(AcademicProgram $program, array $data): AcademicProgram
    {
        if (isset($data['name']) && $program->name !== $data['name'] && empty($data['slug'])) {
            $data['slug'] = CollegeHelper::generateSlug($data['name']);
        }

        $program->update($data);

        return $program->refresh();
    }

    public function allActive(): Collection
    {
        return $this->repository->allActive();
    }

    public function allVisible(): Collection
    {
        return $this->repository->allVisible();
    }

    public function forDepartment(int $departmentId): Collection
    {
        return $this->repository->forDepartment($departmentId);
    }
}
