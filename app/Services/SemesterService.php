<?php

namespace App\Services;

use App\Models\Semester;
use App\Repositories\SemesterRepository;

class SemesterService extends BaseService
{
    public function __construct(SemesterRepository $repository)
    {
        parent::__construct($repository);
    }

    public function createModel(array $data): Semester
    {
        if (! empty($data['is_current'])) {
            Semester::where('is_current', true)->update(['is_current' => false]);
        }
        return Semester::create($data);
    }

    public function updateModel(Semester $semester, array $data): Semester
    {
        if (! empty($data['is_current'])) {
            Semester::where('id', '!=', $semester->id)
                ->where('is_current', true)
                ->update(['is_current' => false]);
        }
        $semester->update($data);
        return $semester->refresh();
    }

    public function setCurrent(Semester $semester): void
    {
        Semester::where('is_current', true)->update(['is_current' => false]);
        $semester->update(['is_current' => true, 'is_active' => true]);
        // Also set its academic year as current
        $semester->academicYear?->update(['is_current' => true]);
    }
}
