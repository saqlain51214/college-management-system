<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Student;
use Illuminate\Database\Seeder;

class ExamResultSeeder extends Seeder
{
    public function run(): void
    {
        $students = Student::whereNotNull('roll_number')->get()->keyBy('roll_number');
        $exams    = Exam::all()->keyBy('title');

        // Helper: calculate grade from marks/total
        $grade = function (float $obtained, float $total): array {
            $pct = $total > 0 ? ($obtained / $total) * 100 : 0;
            if ($pct >= 85) {
                return ['grade' => 'A',  'points' => 4.0];
            } elseif ($pct >= 80) {
                return ['grade' => 'A-', 'points' => 3.7];
            } elseif ($pct >= 75) {
                return ['grade' => 'B+', 'points' => 3.3];
            } elseif ($pct >= 70) {
                return ['grade' => 'B',  'points' => 3.0];
            } elseif ($pct >= 65) {
                return ['grade' => 'B-', 'points' => 2.7];
            } elseif ($pct >= 60) {
                return ['grade' => 'C+', 'points' => 2.3];
            } elseif ($pct >= 55) {
                return ['grade' => 'C',  'points' => 2.0];
            } elseif ($pct >= 50) {
                return ['grade' => 'C-', 'points' => 1.7];
            } elseif ($pct >= 45) {
                return ['grade' => 'D',  'points' => 1.0];
            }
            return ['grade' => 'F', 'points' => 0.0];
        };

        // [ exam_title, roll_number, marks_obtained ]
        $results = [
            // ─── CS-101 Midterm (total 50) ─────────────────────────────────────────
            ['CS-101 Midterm Exam — Fall 2024', 'CS-2024-0001', 43],
            ['CS-101 Midterm Exam — Fall 2024', 'CS-2024-0002', 48],
            ['CS-101 Midterm Exam — Fall 2024', 'CS-2024-0003', 38],
            ['CS-101 Midterm Exam — Fall 2024', 'CS-2024-0004', 45],
            ['CS-101 Midterm Exam — Fall 2024', 'CS-2024-0005', 32],
            ['CS-101 Midterm Exam — Fall 2024', 'CS-2024-0006', 46],
            ['CS-101 Midterm Exam — Fall 2024', 'CS-2024-0007', 35],
            ['CS-101 Midterm Exam — Fall 2024', 'CS-2024-0008', 41],

            // ─── CS-102 Midterm (total 50) ─────────────────────────────────────────
            ['CS-102 Midterm Exam — Fall 2024', 'CS-2024-0001', 40],
            ['CS-102 Midterm Exam — Fall 2024', 'CS-2024-0002', 47],
            ['CS-102 Midterm Exam — Fall 2024', 'CS-2024-0003', 33],
            ['CS-102 Midterm Exam — Fall 2024', 'CS-2024-0004', 44],
            ['CS-102 Midterm Exam — Fall 2024', 'CS-2024-0005', 28],
            ['CS-102 Midterm Exam — Fall 2024', 'CS-2024-0006', 49],
            ['CS-102 Midterm Exam — Fall 2024', 'CS-2024-0007', 36],
            ['CS-102 Midterm Exam — Fall 2024', 'CS-2024-0008', 38],

            // ─── CS-102 Lab Exam (total 50) ────────────────────────────────────────
            ['CS-102 Lab Exam — Fall 2024', 'CS-2024-0001', 42],
            ['CS-102 Lab Exam — Fall 2024', 'CS-2024-0002', 45],
            ['CS-102 Lab Exam — Fall 2024', 'CS-2024-0003', 35],
            ['CS-102 Lab Exam — Fall 2024', 'CS-2024-0004', 40],
            ['CS-102 Lab Exam — Fall 2024', 'CS-2024-0005', 30],
            ['CS-102 Lab Exam — Fall 2024', 'CS-2024-0006', 48],
            ['CS-102 Lab Exam — Fall 2024', 'CS-2024-0007', 33],
            ['CS-102 Lab Exam — Fall 2024', 'CS-2024-0008', 38],

            // ─── CS-301 Midterm (total 50) ─────────────────────────────────────────
            ['CS-301 Midterm Exam — Fall 2024', 'CS-2023-0001', 38],
            ['CS-301 Midterm Exam — Fall 2024', 'CS-2023-0002', 44],
            ['CS-301 Midterm Exam — Fall 2024', 'CS-2023-0004', 35],
            ['CS-301 Midterm Exam — Fall 2024', 'CS-2023-0005', 47],
            ['CS-301 Midterm Exam — Fall 2024', 'CS-2023-0006', 30],

            // ─── CS-301 Quiz 1 (total 20) ──────────────────────────────────────────
            ['CS-301 Quiz 1 — Sorting Algorithms', 'CS-2023-0001', 16],
            ['CS-301 Quiz 1 — Sorting Algorithms', 'CS-2023-0002', 19],
            ['CS-301 Quiz 1 — Sorting Algorithms', 'CS-2023-0004', 14],
            ['CS-301 Quiz 1 — Sorting Algorithms', 'CS-2023-0005', 18],
            ['CS-301 Quiz 1 — Sorting Algorithms', 'CS-2023-0006', 11],

            // ─── CS-302 Midterm (total 50) ─────────────────────────────────────────
            ['CS-302 Midterm Exam — Fall 2024', 'CS-2023-0001', 41],
            ['CS-302 Midterm Exam — Fall 2024', 'CS-2023-0002', 46],
            ['CS-302 Midterm Exam — Fall 2024', 'CS-2023-0004', 37],
            ['CS-302 Midterm Exam — Fall 2024', 'CS-2023-0005', 44],
            ['CS-302 Midterm Exam — Fall 2024', 'CS-2023-0006', 29],

            // ─── CS-302 Quiz 1 (total 20) ──────────────────────────────────────────
            ['CS-302 Quiz 1 — Database Normalization', 'CS-2023-0001', 15],
            ['CS-302 Quiz 1 — Database Normalization', 'CS-2023-0002', 18],
            ['CS-302 Quiz 1 — Database Normalization', 'CS-2023-0004', 13],
            ['CS-302 Quiz 1 — Database Normalization', 'CS-2023-0005', 17],
            ['CS-302 Quiz 1 — Database Normalization', 'CS-2023-0006', 10],

            // ─── CS-302 Lab Exam (total 50) ────────────────────────────────────────
            ['CS-302 Lab Exam — Fall 2024', 'CS-2023-0001', 40],
            ['CS-302 Lab Exam — Fall 2024', 'CS-2023-0002', 45],
            ['CS-302 Lab Exam — Fall 2024', 'CS-2023-0004', 33],
            ['CS-302 Lab Exam — Fall 2024', 'CS-2023-0005', 48],
            ['CS-302 Lab Exam — Fall 2024', 'CS-2023-0006', 27],

            // ─── CS-501 AI Midterm (total 50) ─────────────────────────────────────
            ['CS-501 AI Midterm Exam — Fall 2024', 'CS-2022-0001', 38],
            ['CS-501 AI Midterm Exam — Fall 2024', 'CS-2022-0002', 42],
            ['CS-501 AI Midterm Exam — Fall 2024', 'CS-2022-0004', 45],
            ['CS-501 AI Midterm Exam — Fall 2024', 'CS-2022-0005', 47],
            ['CS-501 AI Midterm Exam — Fall 2024', 'CS-2022-0006', 33],

            // ─── EDU-101 Midterm (total 50) ────────────────────────────────────────
            ['EDU-101 Midterm Exam — Fall 2024', 'EDU-2024-0001', 39],
            ['EDU-101 Midterm Exam — Fall 2024', 'EDU-2024-0002', 35],
            ['EDU-101 Midterm Exam — Fall 2024', 'EDU-2024-0003', 42],
            ['EDU-101 Midterm Exam — Fall 2024', 'EDU-2024-0004', 30],
            ['EDU-101 Midterm Exam — Fall 2024', 'EDU-2024-0005', 44],
            ['EDU-101 Midterm Exam — Fall 2024', 'EDU-2024-0006', 37],

            // ─── EDU-102 Midterm (total 50) ────────────────────────────────────────
            ['EDU-102 Midterm Exam — Fall 2024', 'EDU-2024-0001', 40],
            ['EDU-102 Midterm Exam — Fall 2024', 'EDU-2024-0002', 33],
            ['EDU-102 Midterm Exam — Fall 2024', 'EDU-2024-0003', 44],
            ['EDU-102 Midterm Exam — Fall 2024', 'EDU-2024-0004', 27],
            ['EDU-102 Midterm Exam — Fall 2024', 'EDU-2024-0005', 46],
            ['EDU-102 Midterm Exam — Fall 2024', 'EDU-2024-0006', 38],
        ];

        foreach ($results as [$examTitle, $roll, $marks]) {
            $exam    = $exams[$examTitle] ?? null;
            $student = $students[$roll] ?? null;

            if (! $exam || ! $student) {
                continue;
            }

            $gradeInfo = $grade((float) $marks, (float) $exam->total_marks);

            ExamResult::firstOrCreate(
                ['exam_id' => $exam->id, 'student_id' => $student->id],
                [
                    'exam_id'        => $exam->id,
                    'student_id'     => $student->id,
                    'marks_obtained' => $marks,
                    'grade'          => $gradeInfo['grade'],
                    'grade_points'   => $gradeInfo['points'],
                    'is_absent'      => false,
                    'is_exempted'    => false,
                ]
            );
        }
    }
}
