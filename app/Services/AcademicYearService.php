<?php

namespace App\Services;

use App\Models\AcademicYear;
use App\Repositories\AcademicYearRepository;

class AcademicYearService extends BaseService
{
    public function __construct(AcademicYearRepository $repository)
    {
        parent::__construct($repository);
    }

    public function createModel(array $data): AcademicYear
    {
        if (! empty($data['is_current'])) {
            AcademicYear::where('is_current', true)->update(['is_current' => false]);
        }
        return AcademicYear::create($data);
    }

    public function updateModel(AcademicYear $year, array $data): AcademicYear
    {
        if (! empty($data['is_current'])) {
            // Unset current from all others first
            AcademicYear::where('id', '!=', $year->id)
                ->where('is_current', true)
                ->update(['is_current' => false]);
        }
        $year->update($data);
        return $year->refresh();
    }

    public function setCurrent(AcademicYear $year): void
    {
        AcademicYear::where('is_current', true)->update(['is_current' => false]);
        $year->update(['is_current' => true, 'is_active' => true]);
    }
}
