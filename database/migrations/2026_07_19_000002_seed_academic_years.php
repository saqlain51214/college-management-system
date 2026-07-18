<?php

use App\Models\AcademicYear;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Seed the common academic years so the "Academic Year" dropdowns
     * (fee challans, students) have real values to pick from.
     */
    public function up(): void
    {
        $years = [
            ['name' => '2024-2025', 'start' => '2024-09-01', 'end' => '2025-08-31', 'current' => false],
            ['name' => '2025-2026', 'start' => '2025-09-01', 'end' => '2026-08-31', 'current' => true],
            ['name' => '2026-2027', 'start' => '2026-09-01', 'end' => '2027-08-31', 'current' => false],
        ];

        foreach ($years as $y) {
            AcademicYear::updateOrCreate(
                ['name' => $y['name']],
                [
                    'start_date' => $y['start'],
                    'end_date'   => $y['end'],
                    'is_current' => $y['current'],
                    'is_active'  => true,
                ]
            );
        }
    }

    public function down(): void
    {
        AcademicYear::whereIn('name', ['2024-2025', '2025-2026', '2026-2027'])->delete();
    }
};
