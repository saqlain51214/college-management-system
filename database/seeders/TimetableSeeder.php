<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\Timetable;
use Illuminate\Database\Seeder;

class TimetableSeeder extends Seeder
{
    public function run(): void
    {
        $year = AcademicYear::where('is_current', true)->first()
            ?? AcademicYear::where('name', 'like', '%2024%')->first();

        if (! $year) {
            $this->command->warn('No academic year found. Skipping timetable seeder.');
            return;
        }

        $courses  = Course::all()->keyBy('code');
        $teachers = Teacher::all()->keyBy('employee_id');

        // [course_code, employee_id, day, start, end, room, section, program_id, semester]
        $slots = [

            // ═══════════════════════════════════════════════════════════
            // BS Computer Science (Program 3) — Semester 1
            // Courses: CS-101, CS-102, MATH-101, ENG-101, ISL-101
            // ═══════════════════════════════════════════════════════════
            ['CS-101',  'EMP-0010', 'monday',    '08:00', '09:00', 'CS Lab 1',   'A', 3, 1],
            ['CS-101',  'EMP-0010', 'wednesday', '08:00', '09:00', 'CS Lab 1',   'A', 3, 1],
            ['CS-101',  'EMP-0010', 'friday',    '08:00', '09:00', 'CS Lab 1',   'A', 3, 1],

            ['CS-102',  'EMP-0010', 'monday',    '09:00', '10:00', 'CS Lab 1',   'A', 3, 1],
            ['CS-102',  'EMP-0010', 'tuesday',   '08:00', '10:00', 'CS Lab 1',   'A', 3, 1], // 2-hr lab
            ['CS-102',  'EMP-0010', 'thursday',  '09:00', '10:00', 'CS Lab 1',   'A', 3, 1],

            ['MATH-101','EMP-0012', 'monday',    '10:15', '11:15', 'Room 101',   'A', 3, 1],
            ['MATH-101','EMP-0012', 'wednesday', '10:15', '11:15', 'Room 101',   'A', 3, 1],
            ['MATH-101','EMP-0012', 'saturday',  '08:00', '09:00', 'Room 101',   'A', 3, 1],

            ['ENG-101', 'EMP-0006', 'tuesday',   '10:15', '11:15', 'Room 102',   'A', 3, 1],
            ['ENG-101', 'EMP-0006', 'thursday',  '10:15', '11:15', 'Room 102',   'A', 3, 1],
            ['ENG-101', 'EMP-0006', 'saturday',  '09:00', '10:00', 'Room 102',   'A', 3, 1],

            ['ISL-101', 'EMP-0015', 'monday',    '11:15', '12:15', 'Room 103',   'A', 3, 1],
            ['ISL-101', 'EMP-0015', 'wednesday', '11:15', '12:15', 'Room 103',   'A', 3, 1],
            ['ISL-101', 'EMP-0015', 'friday',    '09:00', '10:00', 'Room 103',   'A', 3, 1],

            // ═══════════════════════════════════════════════════════════
            // BS Computer Science (Program 3) — Semester 2
            // Courses: CS-201 (OOP), MATH-201 (Discrete), CS-202 (Digital Logic)
            // ═══════════════════════════════════════════════════════════
            ['CS-201',  'EMP-0010', 'monday',    '08:00', '09:00', 'CS Lab 2',   'A', 3, 2],
            ['CS-201',  'EMP-0010', 'tuesday',   '08:00', '10:00', 'CS Lab 2',   'A', 3, 2], // 2-hr lab
            ['CS-201',  'EMP-0010', 'thursday',  '08:00', '09:00', 'CS Lab 2',   'A', 3, 2],

            ['MATH-201','EMP-0012', 'monday',    '09:00', '10:00', 'Room 101',   'A', 3, 2],
            ['MATH-201','EMP-0012', 'wednesday', '09:00', '10:00', 'Room 101',   'A', 3, 2],
            ['MATH-201','EMP-0012', 'saturday',  '08:00', '09:00', 'Room 101',   'A', 3, 2],

            ['CS-202',  'EMP-0007', 'tuesday',   '10:15', '11:15', 'Room 201',   'A', 3, 2],
            ['CS-202',  'EMP-0007', 'thursday',  '10:15', '11:15', 'Room 201',   'A', 3, 2],
            ['CS-202',  'EMP-0007', 'saturday',  '09:00', '10:00', 'Room 201',   'A', 3, 2],

            // ═══════════════════════════════════════════════════════════
            // BS Computer Science (Program 3) — Semester 3
            // Courses: CS-301 (DSA), CS-302 (Database)
            // ═══════════════════════════════════════════════════════════
            ['CS-301',  'EMP-0010', 'monday',    '12:00', '13:00', 'CS Lab 2',   'A', 3, 3],
            ['CS-301',  'EMP-0010', 'wednesday', '12:00', '14:00', 'CS Lab 2',   'A', 3, 3], // 2-hr lab
            ['CS-301',  'EMP-0010', 'friday',    '10:15', '11:15', 'CS Lab 2',   'A', 3, 3],

            ['CS-302',  'EMP-0003', 'tuesday',   '12:00', '13:00', 'CS Lab 3',   'A', 3, 3],
            ['CS-302',  'EMP-0003', 'thursday',  '12:00', '14:00', 'CS Lab 3',   'A', 3, 3], // 2-hr lab
            ['CS-302',  'EMP-0003', 'saturday',  '10:15', '11:15', 'CS Lab 3',   'A', 3, 3],

            // ═══════════════════════════════════════════════════════════
            // BS Computer Science (Program 3) — Semester 4
            // Courses: CS-303 (Software Engineering)
            // ═══════════════════════════════════════════════════════════
            ['CS-303',  'EMP-0002', 'monday',    '08:00', '09:00', 'Room 202',   'A', 3, 4],
            ['CS-303',  'EMP-0002', 'wednesday', '08:00', '09:00', 'Room 202',   'A', 3, 4],
            ['CS-303',  'EMP-0002', 'friday',    '08:00', '09:00', 'Room 202',   'A', 3, 4],

            // ═══════════════════════════════════════════════════════════
            // BS Computer Science (Program 3) — Semester 5
            // Courses: CS-501 (AI), CS-502 (Web Tech)
            // ═══════════════════════════════════════════════════════════
            ['CS-501',  'EMP-0001', 'monday',    '14:00', '15:00', 'CS Lab 1',   'A', 3, 5],
            ['CS-501',  'EMP-0001', 'wednesday', '14:00', '15:00', 'CS Lab 1',   'A', 3, 5],
            ['CS-501',  'EMP-0001', 'friday',    '14:00', '15:00', 'CS Lab 1',   'A', 3, 5],

            ['CS-502',  'EMP-0009', 'tuesday',   '13:00', '14:00', 'CS Lab 2',   'A', 3, 5],
            ['CS-502',  'EMP-0009', 'thursday',  '13:00', '15:00', 'CS Lab 2',   'A', 3, 5], // 2-hr lab
            ['CS-502',  'EMP-0009', 'saturday',  '11:15', '12:15', 'CS Lab 2',   'A', 3, 5],

            // ═══════════════════════════════════════════════════════════
            // Bachelor of Education (Program 1) — Semester 1
            // Courses: EDU-101 (Foundations), EDU-102 (Educational Psychology)
            // ═══════════════════════════════════════════════════════════
            ['EDU-101', 'EMP-0005', 'monday',    '08:00', '09:00', 'Room 301',   'A', 1, 1],
            ['EDU-101', 'EMP-0005', 'wednesday', '08:00', '09:00', 'Room 301',   'A', 1, 1],
            ['EDU-101', 'EMP-0005', 'friday',    '08:00', '09:00', 'Room 301',   'A', 1, 1],

            ['EDU-102', 'EMP-0004', 'tuesday',   '08:00', '09:00', 'Room 302',   'A', 1, 1],
            ['EDU-102', 'EMP-0004', 'thursday',  '08:00', '09:00', 'Room 302',   'A', 1, 1],
            ['EDU-102', 'EMP-0004', 'saturday',  '08:00', '09:00', 'Room 302',   'A', 1, 1],

            // ═══════════════════════════════════════════════════════════
            // Bachelor of Education (Program 1) — Semester 2
            // Courses: EDU-201 (Classroom Management)
            // ═══════════════════════════════════════════════════════════
            ['EDU-201', 'EMP-0005', 'monday',    '10:15', '11:15', 'Room 303',   'A', 1, 2],
            ['EDU-201', 'EMP-0005', 'wednesday', '10:15', '11:15', 'Room 303',   'A', 1, 2],
            ['EDU-201', 'EMP-0005', 'saturday',  '09:00', '10:00', 'Room 303',   'A', 1, 2],
        ];

        $inserted = 0;
        foreach ($slots as $s) {
            $course  = $courses->get($s[0]);
            $teacher = $teachers->get($s[1]);

            if (! $course || ! $teacher) {
                $this->command->warn("Skipped: course={$s[0]}, teacher={$s[1]}");
                continue;
            }

            Timetable::firstOrCreate(
                [
                    'course_id'          => $course->id,
                    'teacher_id'         => $teacher->id,
                    'academic_year_id'   => $year->id,
                    'day_of_week'        => $s[2],
                    'start_time'         => $s[3],
                ],
                [
                    'academic_program_id' => $s[7],
                    'semester'            => $s[8],
                    'semester_number'     => $s[8],
                    'end_time'            => $s[4],
                    'room'                => $s[5],
                    'section'             => $s[6],
                    'is_active'           => true,
                ]
            );
            $inserted++;
        }

        $this->command->info("✅ {$inserted} timetable slots seeded for year: {$year->name}");
    }
}
