<?php

namespace Database\Seeders;

use App\Enums\FeeTypeEnum;
use App\Models\AcademicProgram;
use App\Models\AcademicYear;
use App\Models\FeeStructure;
use Illuminate\Database\Seeder;

/**
 * One real Tuition Fee structure per JDCA programme (see
 * JdcaProgramsSeeder::programmes() — the authoritative programme list), for
 * the current academic year. Uniform Rs. 10,000/semester per admin request —
 * amounts, due dates, and late fines are all still editable per-programme
 * from Fees & Billing -> Fee Structures afterward.
 */
class JdcaTuitionFeeSeeder extends Seeder
{
    public const AMOUNT = 10000;
    public const LATE_FINE_PER_DAY = 50;

    public function run(): void
    {
        $year = AcademicYear::getCurrent() ?? AcademicYear::query()->orderByDesc('name')->first();

        if (! $year) {
            AcademicYear::ensureDefaults();
            $year = AcademicYear::getCurrent();
        }

        foreach (JdcaProgramsSeeder::programmes() as $p) {
            $program = AcademicProgram::where('slug', $p['slug'])->first();

            if (! $program) {
                continue;
            }

            FeeStructure::firstOrCreate(
                [
                    'academic_program_id' => $program->id,
                    'academic_year_id'    => $year->id,
                    'fee_type'            => FeeTypeEnum::Tuition->value,
                ],
                [
                    'title'             => $program->name . ' — Tuition Fee (' . $year->name . ')',
                    'semester_number'   => null,
                    'amount'            => self::AMOUNT,
                    'late_fine_per_day' => self::LATE_FINE_PER_DAY,
                    'frequency'         => 'semester',
                    'is_mandatory'      => true,
                    'is_active'         => true,
                ]
            );
        }
    }
}
