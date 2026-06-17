<?php

namespace Database\Seeders;

use App\Enums\ExamTypeEnum;
use App\Models\AcademicProgram;
use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\Exam;
use Illuminate\Database\Seeder;

class ExamSeeder extends Seeder
{
    public function run(): void
    {
        $progCS  = AcademicProgram::where('code', 'BS-CS')->value('id');
        $progBEd = AcademicProgram::where('code', 'BED')->value('id');

        $year2024 = AcademicYear::where('name', '2024-2025')->value('id');
        $year2023 = AcademicYear::where('name', '2023-2024')->value('id');

        $courses = Course::all()->keyBy('code');

        $cs101  = $courses['CS-101']?->id;
        $cs102  = $courses['CS-102']?->id;
        $cs201  = $courses['CS-201']?->id;
        $cs301  = $courses['CS-301']?->id;
        $cs302  = $courses['CS-302']?->id;
        $cs303  = $courses['CS-303']?->id;
        $cs501  = $courses['CS-501']?->id;
        $cs502  = $courses['CS-502']?->id;
        $math101= $courses['MATH-101']?->id;
        $eng101 = $courses['ENG-101']?->id;
        $isl101 = $courses['ISL-101']?->id;
        $edu101 = $courses['EDU-101']?->id;
        $edu102 = $courses['EDU-102']?->id;
        $edu201 = $courses['EDU-201']?->id;

        $mid  = ExamTypeEnum::Midterm->value;
        $fin  = ExamTypeEnum::Final->value;
        $quiz = ExamTypeEnum::Quiz->value;
        $lab  = ExamTypeEnum::Lab->value;
        $viva = ExamTypeEnum::Viva->value;

        $exams = [
            // ─── BS CS Semester 1 — Fall 2024 ─────────────────────────────────────
            ['title' => 'CS-101 Midterm Exam — Fall 2024',         'type' => $mid,  'course' => $cs101,   'prog' => $progCS,  'year' => $year2024, 'sem' => 1, 'date' => '2024-11-15', 'time' => '09:00', 'dur' => 120, 'total' => 50,  'pass' => 20, 'wt' => 30, 'venue' => 'Hall A-101', 'pub' => true,  'res' => true],
            ['title' => 'CS-102 Midterm Exam — Fall 2024',         'type' => $mid,  'course' => $cs102,   'prog' => $progCS,  'year' => $year2024, 'sem' => 1, 'date' => '2024-11-18', 'time' => '11:00', 'dur' => 120, 'total' => 50,  'pass' => 20, 'wt' => 30, 'venue' => 'Hall A-102', 'pub' => true,  'res' => true],
            ['title' => 'MATH-101 Midterm Exam — Fall 2024',       'type' => $mid,  'course' => $math101, 'prog' => $progCS,  'year' => $year2024, 'sem' => 1, 'date' => '2024-11-20', 'time' => '09:00', 'dur' => 90,  'total' => 50,  'pass' => 20, 'wt' => 30, 'venue' => 'Hall B-201', 'pub' => true,  'res' => true],
            ['title' => 'ENG-101 Midterm Exam — Fall 2024',        'type' => $mid,  'course' => $eng101,  'prog' => $progCS,  'year' => $year2024, 'sem' => 1, 'date' => '2024-11-22', 'time' => '11:00', 'dur' => 90,  'total' => 50,  'pass' => 20, 'wt' => 30, 'venue' => 'Hall A-103', 'pub' => true,  'res' => true],
            ['title' => 'CS-101 Final Exam — Fall 2024',           'type' => $fin,  'course' => $cs101,   'prog' => $progCS,  'year' => $year2024, 'sem' => 1, 'date' => '2025-01-20', 'time' => '09:00', 'dur' => 180, 'total' => 100, 'pass' => 40, 'wt' => 70, 'venue' => 'Exam Hall 1', 'pub' => true,  'res' => false],
            ['title' => 'CS-102 Final Exam — Fall 2024',           'type' => $fin,  'course' => $cs102,   'prog' => $progCS,  'year' => $year2024, 'sem' => 1, 'date' => '2025-01-23', 'time' => '11:00', 'dur' => 180, 'total' => 100, 'pass' => 40, 'wt' => 70, 'venue' => 'Exam Hall 1', 'pub' => true,  'res' => false],
            ['title' => 'CS-102 Lab Exam — Fall 2024',             'type' => $lab,  'course' => $cs102,   'prog' => $progCS,  'year' => $year2024, 'sem' => 1, 'date' => '2025-01-10', 'time' => '09:00', 'dur' => 120, 'total' => 50,  'pass' => 20, 'wt' => 20, 'venue' => 'CS Lab 1',    'pub' => true,  'res' => true],

            // ─── BS CS Semester 3 — Fall 2024 ─────────────────────────────────────
            ['title' => 'CS-301 Midterm Exam — Fall 2024',         'type' => $mid,  'course' => $cs301,   'prog' => $progCS,  'year' => $year2024, 'sem' => 3, 'date' => '2024-11-14', 'time' => '09:00', 'dur' => 120, 'total' => 50,  'pass' => 20, 'wt' => 30, 'venue' => 'Hall A-201', 'pub' => true,  'res' => true],
            ['title' => 'CS-302 Midterm Exam — Fall 2024',         'type' => $mid,  'course' => $cs302,   'prog' => $progCS,  'year' => $year2024, 'sem' => 3, 'date' => '2024-11-16', 'time' => '11:00', 'dur' => 120, 'total' => 50,  'pass' => 20, 'wt' => 30, 'venue' => 'Hall A-202', 'pub' => true,  'res' => true],
            ['title' => 'CS-302 Lab Exam — Fall 2024',             'type' => $lab,  'course' => $cs302,   'prog' => $progCS,  'year' => $year2024, 'sem' => 3, 'date' => '2025-01-08', 'time' => '09:00', 'dur' => 120, 'total' => 50,  'pass' => 20, 'wt' => 20, 'venue' => 'CS Lab 2',    'pub' => true,  'res' => true],
            ['title' => 'CS-301 Final Exam — Fall 2024',           'type' => $fin,  'course' => $cs301,   'prog' => $progCS,  'year' => $year2024, 'sem' => 3, 'date' => '2025-01-24', 'time' => '09:00', 'dur' => 180, 'total' => 100, 'pass' => 40, 'wt' => 70, 'venue' => 'Exam Hall 2', 'pub' => true,  'res' => false],

            // ─── BS CS Semester 5 — Fall 2024 ─────────────────────────────────────
            ['title' => 'CS-501 AI Midterm Exam — Fall 2024',      'type' => $mid,  'course' => $cs501,   'prog' => $progCS,  'year' => $year2024, 'sem' => 5, 'date' => '2024-11-19', 'time' => '09:00', 'dur' => 120, 'total' => 50,  'pass' => 20, 'wt' => 30, 'venue' => 'Hall B-101', 'pub' => true,  'res' => true],
            ['title' => 'CS-502 Web Tech Midterm — Fall 2024',     'type' => $mid,  'course' => $cs502,   'prog' => $progCS,  'year' => $year2024, 'sem' => 5, 'date' => '2024-11-21', 'time' => '11:00', 'dur' => 90,  'total' => 50,  'pass' => 20, 'wt' => 30, 'venue' => 'Hall B-102', 'pub' => true,  'res' => true],

            // ─── B.Ed Semester 1 — Fall 2024 ──────────────────────────────────────
            ['title' => 'EDU-101 Midterm Exam — Fall 2024',        'type' => $mid,  'course' => $edu101,  'prog' => $progBEd, 'year' => $year2024, 'sem' => 1, 'date' => '2024-11-17', 'time' => '10:00', 'dur' => 90,  'total' => 50,  'pass' => 20, 'wt' => 30, 'venue' => 'Hall C-101', 'pub' => true,  'res' => true],
            ['title' => 'EDU-102 Midterm Exam — Fall 2024',        'type' => $mid,  'course' => $edu102,  'prog' => $progBEd, 'year' => $year2024, 'sem' => 1, 'date' => '2024-11-19', 'time' => '10:00', 'dur' => 90,  'total' => 50,  'pass' => 20, 'wt' => 30, 'venue' => 'Hall C-102', 'pub' => true,  'res' => true],
            ['title' => 'EDU-101 Final Exam — Fall 2024',          'type' => $fin,  'course' => $edu101,  'prog' => $progBEd, 'year' => $year2024, 'sem' => 1, 'date' => '2025-01-22', 'time' => '10:00', 'dur' => 150, 'total' => 100, 'pass' => 40, 'wt' => 70, 'venue' => 'Exam Hall 3', 'pub' => true,  'res' => false],

            // ─── Quiz ──────────────────────────────────────────────────────────────
            ['title' => 'CS-302 Quiz 1 — Database Normalization',  'type' => $quiz, 'course' => $cs302,   'prog' => $progCS,  'year' => $year2024, 'sem' => 3, 'date' => '2024-10-10', 'time' => '11:00', 'dur' => 20,  'total' => 20,  'pass' => 8,  'wt' => 5,  'venue' => 'Class Room B-201', 'pub' => true, 'res' => true],
            ['title' => 'CS-301 Quiz 1 — Sorting Algorithms',      'type' => $quiz, 'course' => $cs301,   'prog' => $progCS,  'year' => $year2024, 'sem' => 3, 'date' => '2024-10-08', 'time' => '09:00', 'dur' => 20,  'total' => 20,  'pass' => 8,  'wt' => 5,  'venue' => 'Class Room A-201', 'pub' => true, 'res' => true],
        ];

        foreach ($exams as $e) {
            Exam::firstOrCreate(
                ['title' => $e['title']],
                [
                    'course_id'           => $e['course'],
                    'academic_program_id' => $e['prog'],
                    'academic_year_id'    => $e['year'],
                    'exam_type'           => $e['type'],
                    'semester_number'     => $e['sem'],
                    'exam_date'           => $e['date'],
                    'start_time'          => $e['time'],
                    'duration_minutes'    => $e['dur'],
                    'total_marks'         => $e['total'],
                    'passing_marks'       => $e['pass'],
                    'weightage_percent'   => $e['wt'],
                    'venue'               => $e['venue'],
                    'is_published'        => $e['pub'],
                    'results_published'   => $e['res'],
                ]
            );
        }
    }
}
