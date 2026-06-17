<?php

namespace App\Services;

use App\Helpers\CollegeHelper;
use App\Models\Course;
use App\Repositories\CourseRepository;
use Illuminate\Database\Eloquent\Collection;

class CourseService extends BaseService
{
    public function __construct(CourseRepository $repository)
    {
        parent::__construct($repository);
    }

    public function createModel(array $data): Course
    {
        if (empty($data['slug'])) {
            $data['slug'] = CollegeHelper::generateSlug($data['name']);
        }
        return Course::create($data);
    }

    public function updateModel(Course $course, array $data): Course
    {
        if (isset($data['name']) && $course->name !== $data['name'] && empty($data['slug'])) {
            $data['slug'] = CollegeHelper::generateSlug($data['name']);
        }
        $course->update($data);
        return $course->refresh();
    }

    public function forProgram(int $programId): Collection
    {
        return $this->repository->forProgram($programId);
    }

    public function forDepartment(int $deptId): Collection
    {
        return $this->repository->forDepartment($deptId);
    }
}
