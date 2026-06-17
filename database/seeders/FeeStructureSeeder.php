<?php

namespace Database\Seeders;

use App\Enums\FeeTypeEnum;
use App\Models\AcademicProgram;
use App\Models\AcademicYear;
use App\Models\FeeStructure;
use Illuminate\Database\Seeder;

class FeeStructureSeeder extends Seeder
{
    public function run(): void
    {
        $csProgram  = AcademicProgram::where('code', 'BS-CS')->value('id');
        $bedProgram = AcademicProgram::where('code', 'BED')->value('id');
        $year2024   = AcademicYear::where('name', '2024-2025')->value('id');
        $year2023   = AcademicYear::where('name', '2023-2024')->value('id');

        $fees = [
            // BS CS — 2024-2025
            ['title' => 'BS CS Tuition Fee — Per Semester 2024-25',   'type' => FeeTypeEnum::Tuition,   'prog' => $csProgram,  'year' => $year2024, 'amount' => 25000, 'freq' => 'semester', 'sem' => null, 'fine' => 100],
            ['title' => 'BS CS Admission Fee (One Time) 2024-25',      'type' => FeeTypeEnum::Admission, 'prog' => $csProgram,  'year' => $year2024, 'amount' => 15000, 'freq' => 'one_time', 'sem' => 1,   'fine' => 200],
            ['title' => 'BS CS Examination Fee — Per Semester 2024-25','type' => FeeTypeEnum::Exam,      'prog' => $csProgram,  'year' => $year2024, 'amount' =>  3000, 'freq' => 'semester', 'sem' => null, 'fine' =>  50],
            ['title' => 'BS CS Lab Fee — Per Semester 2024-25',        'type' => FeeTypeEnum::Lab,       'prog' => $csProgram,  'year' => $year2024, 'amount' =>  2500, 'freq' => 'semester', 'sem' => null, 'fine' =>  50],
            ['title' => 'BS CS Hostel Fee — Annual 2024-25',           'type' => FeeTypeEnum::Hostel,    'prog' => $csProgram,  'year' => $year2024, 'amount' => 48000, 'freq' => 'annual',   'sem' => null, 'fine' => 200, 'mandatory' => false],
            ['title' => 'BS CS Transport Fee — Monthly 2024-25',       'type' => FeeTypeEnum::Transport, 'prog' => $csProgram,  'year' => $year2024, 'amount' =>  3000, 'freq' => 'monthly',  'sem' => null, 'fine' =>  50, 'mandatory' => false],

            // B.Ed — 2024-2025
            ['title' => 'B.Ed Tuition Fee — Per Semester 2024-25',     'type' => FeeTypeEnum::Tuition,   'prog' => $bedProgram, 'year' => $year2024, 'amount' => 20000, 'freq' => 'semester', 'sem' => null, 'fine' => 100],
            ['title' => 'B.Ed Admission Fee (One Time) 2024-25',       'type' => FeeTypeEnum::Admission, 'prog' => $bedProgram, 'year' => $year2024, 'amount' => 10000, 'freq' => 'one_time', 'sem' => 1,   'fine' => 200],
            ['title' => 'B.Ed Examination Fee — Per Semester 2024-25', 'type' => FeeTypeEnum::Exam,      'prog' => $bedProgram, 'year' => $year2024, 'amount' =>  2500, 'freq' => 'semester', 'sem' => null, 'fine' =>  50],
            ['title' => 'B.Ed Practical/Teaching Fee 2024-25',         'type' => FeeTypeEnum::Lab,       'prog' => $bedProgram, 'year' => $year2024, 'amount' =>  2000, 'freq' => 'semester', 'sem' => null, 'fine' =>  50],

            // General Fees — All Programs
            ['title' => 'Library Fee — Annual 2024-25',                'type' => FeeTypeEnum::Library,   'prog' => null,        'year' => $year2024, 'amount' =>  1000, 'freq' => 'annual',   'sem' => null, 'fine' =>  20],
            ['title' => 'Sports Fee — Annual 2024-25',                 'type' => FeeTypeEnum::Sports,    'prog' => null,        'year' => $year2024, 'amount' =>   500, 'freq' => 'annual',   'sem' => null, 'fine' =>  10],
            ['title' => 'Registration / Enrolment Fee 2024-25',        'type' => FeeTypeEnum::Other,     'prog' => null,        'year' => $year2024, 'amount' =>  1500, 'freq' => 'one_time', 'sem' => 1,   'fine' => 100],
            ['title' => 'Development Fund — Annual 2024-25',           'type' => FeeTypeEnum::Other,     'prog' => null,        'year' => $year2024, 'amount' =>  2000, 'freq' => 'annual',   'sem' => null, 'fine' =>  50],
            ['title' => 'Medical / Health Fund — Annual 2024-25',      'type' => FeeTypeEnum::Other,     'prog' => null,        'year' => $year2024, 'amount' =>   750, 'freq' => 'annual',   'sem' => null, 'fine' =>  10, 'mandatory' => false],

            // BS CS — 2023-2024 (previous year)
            ['title' => 'BS CS Tuition Fee — Per Semester 2023-24',   'type' => FeeTypeEnum::Tuition,   'prog' => $csProgram,  'year' => $year2023, 'amount' => 23000, 'freq' => 'semester', 'sem' => null, 'fine' => 100],
            ['title' => 'BS CS Examination Fee — Per Semester 2023-24','type' => FeeTypeEnum::Exam,     'prog' => $csProgram,  'year' => $year2023, 'amount' =>  2800, 'freq' => 'semester', 'sem' => null, 'fine' =>  50],
            ['title' => 'B.Ed Tuition Fee — Per Semester 2023-24',     'type' => FeeTypeEnum::Tuition,  'prog' => $bedProgram, 'year' => $year2023, 'amount' => 18000, 'freq' => 'semester', 'sem' => null, 'fine' => 100],
        ];

        foreach ($fees as $f) {
            FeeStructure::firstOrCreate(
                ['title' => $f['title']],
                [
                    'academic_program_id' => $f['prog'],
                    'academic_year_id'    => $f['year'],
                    'fee_type'            => $f['type']->value,
                    'amount'              => $f['amount'],
                    'late_fine_per_day'   => $f['fine'],
                    'frequency'           => $f['freq'],
                    'semester_number'     => $f['sem'],
                    'is_mandatory'        => $f['mandatory'] ?? true,
                    'is_active'           => true,
                ]
            );
        }
    }
}
