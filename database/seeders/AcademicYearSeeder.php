<?php

namespace Database\Seeders;

use App\Enums\SemesterTypeEnum;
use App\Models\AcademicYear;
use App\Models\Semester;
use Illuminate\Database\Seeder;

class AcademicYearSeeder extends Seeder
{
    public function run(): void
    {
        $years = [
            [
                'name'        => '2022-2023',
                'start_date'  => '2022-08-01',
                'end_date'    => '2023-06-30',
                'is_current'  => false,
                'is_active'   => false,
                'description' => 'Academic year 2022-2023 — completed.',
                'semesters'   => [
                    ['type' => SemesterTypeEnum::Fall,   'name' => 'Fall 2022',   'number' => 1, 'start' => '2022-08-15', 'end' => '2022-12-31', 'current' => false, 'sort' => 1],
                    ['type' => SemesterTypeEnum::Spring, 'name' => 'Spring 2023', 'number' => 2, 'start' => '2023-01-16', 'end' => '2023-05-31', 'current' => false, 'sort' => 2],
                ],
            ],
            [
                'name'        => '2023-2024',
                'start_date'  => '2023-08-01',
                'end_date'    => '2024-06-30',
                'is_current'  => false,
                'is_active'   => false,
                'description' => 'Academic year 2023-2024 — completed.',
                'semesters'   => [
                    ['type' => SemesterTypeEnum::Fall,   'name' => 'Fall 2023',   'number' => 1, 'start' => '2023-08-14', 'end' => '2023-12-30', 'current' => false, 'sort' => 1],
                    ['type' => SemesterTypeEnum::Spring, 'name' => 'Spring 2024', 'number' => 2, 'start' => '2024-01-15', 'end' => '2024-05-31', 'current' => false, 'sort' => 2],
                ],
            ],
            [
                'name'        => '2024-2025',
                'start_date'  => '2024-08-01',
                'end_date'    => '2025-06-30',
                'is_current'  => true,
                'is_active'   => true,
                'description' => 'Current academic year 2024-2025.',
                'semesters'   => [
                    ['type' => SemesterTypeEnum::Fall,   'name' => 'Fall 2024',   'number' => 1, 'start' => '2024-08-12', 'end' => '2024-12-31', 'current' => false, 'sort' => 1],
                    ['type' => SemesterTypeEnum::Spring, 'name' => 'Spring 2025', 'number' => 2, 'start' => '2025-01-13', 'end' => '2025-05-31', 'current' => true,  'sort' => 2],
                ],
            ],
            [
                'name'        => '2025-2026',
                'start_date'  => '2025-08-01',
                'end_date'    => '2026-06-30',
                'is_current'  => false,
                'is_active'   => true,
                'description' => 'Upcoming academic year 2025-2026.',
                'semesters'   => [
                    ['type' => SemesterTypeEnum::Fall,   'name' => 'Fall 2025',   'number' => 1, 'start' => '2025-08-11', 'end' => '2025-12-31', 'current' => false, 'sort' => 1],
                    ['type' => SemesterTypeEnum::Spring, 'name' => 'Spring 2026', 'number' => 2, 'start' => '2026-01-12', 'end' => '2026-05-31', 'current' => false, 'sort' => 2],
                ],
            ],
        ];

        foreach ($years as $yearData) {
            $semesters = $yearData['semesters'];
            unset($yearData['semesters']);

            $year = AcademicYear::firstOrCreate(
                ['name' => $yearData['name']],
                $yearData
            );

            foreach ($semesters as $s) {
                Semester::firstOrCreate(
                    ['academic_year_id' => $year->id, 'name' => $s['name']],
                    [
                        'academic_year_id'  => $year->id,
                        'type'              => $s['type']->value,
                        'name'              => $s['name'],
                        'number'            => $s['number'],
                        'start_date'        => $s['start'],
                        'end_date'          => $s['end'],
                        'is_current'        => $s['current'],
                        'is_active'         => true,
                        'sort_order'        => $s['sort'],
                    ]
                );
            }
        }
    }
}
