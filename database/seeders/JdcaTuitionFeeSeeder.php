<?php

namespace Database\Seeders;

use App\Enums\FeeTypeEnum;
use App\Models\AcademicProgram;
use App\Models\FeeStructure;
use Illuminate\Database\Seeder;

/**
 * One real Tuition Fee structure per JDCA programme (see
 * JdcaProgramsSeeder::programmes() — the authoritative programme list).
 * Uniform Rs. 10,000/semester per admin request — amount, due date, and
 * late fine are all still editable per-programme from Fees & Billing ->
 * Fee Structures afterward.
 *
 * academic_year_id is deliberately left NULL ("applies to any year") — a
 * FeeStructure row is meant to be evergreen and revised in place via the
 * "Update Amount" action (which logs a FeeStructureRevision), not recreated
 * every academic year. Pinning it to one specific year would make it stop
 * matching students admitted in any other year the moment that year is no
 * longer "current".
 */
class JdcaTuitionFeeSeeder extends Seeder
{
    public const AMOUNT = 10000;
    public const LATE_FINE_PER_DAY = 50;

    public function run(): void
    {
        foreach (JdcaProgramsSeeder::programmes() as $p) {
            $program = AcademicProgram::where('slug', $p['slug'])->first();

            if (! $program) {
                continue;
            }

            FeeStructure::firstOrCreate(
                [
                    'academic_program_id' => $program->id,
                    'academic_year_id'    => null,
                    'fee_type'            => FeeTypeEnum::Tuition->value,
                ],
                [
                    'title'             => $program->name . ' — Tuition Fee',
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
