<?php

namespace Database\Seeders;

use App\Enums\AttendanceStatusEnum;
use App\Models\AcademicProgram;
use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\Course;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $courses  = Course::all()->keyBy('code');
        $teachers = Teacher::all()->keyBy('employee_id');
        $students = Student::where('is_active', true)->get()->keyBy('roll_number');

        $progCS  = AcademicProgram::where('code', 'BS-CS')->value('id');
        $progBEd = AcademicProgram::where('code', 'BED')->value('id');

        $cs101  = $courses['CS-101']?->id;
        $cs102  = $courses['CS-102']?->id;
        $cs301  = $courses['CS-301']?->id;
        $cs302  = $courses['CS-302']?->id;
        $cs501  = $courses['CS-501']?->id;
        $edu101 = $courses['EDU-101']?->id;
        $edu102 = $courses['EDU-102']?->id;

        $tAsif   = $teachers['EMP-0001']?->id;
        $tNadia  = $teachers['EMP-0002']?->id;
        $tBilal  = $teachers['EMP-0003']?->id;
        $tRukh   = $teachers['EMP-0004']?->id;

        $P  = AttendanceStatusEnum::Present->value;
        $Ab = AttendanceStatusEnum::Absent->value;
        $L  = AttendanceStatusEnum::Late->value;
        $Lv = AttendanceStatusEnum::Leave->value;

        // Sessions: [course_id, teacher_id, program_id, semester_number, date, start, end, topic]
        $sessions = [
            // CS-101 sessions (Sem 1, CS)
            [$cs101, $tNadia, $progCS, 1, '2024-09-09', '08:00', '09:00', 'Introduction to Computers & Components'],
            [$cs101, $tNadia, $progCS, 1, '2024-09-11', '08:00', '09:00', 'Types of Software: System & Application'],
            [$cs101, $tNadia, $progCS, 1, '2024-09-16', '08:00', '09:00', 'Number Systems — Binary & Decimal'],
            [$cs101, $tNadia, $progCS, 1, '2024-09-18', '08:00', '09:00', 'Hexadecimal & Octal Number Systems'],
            [$cs101, $tNadia, $progCS, 1, '2024-09-23', '08:00', '09:00', 'Input and Output Devices'],

            // CS-102 sessions (Sem 1, CS)
            [$cs102, $tNadia, $progCS, 1, '2024-09-09', '09:00', '10:00', 'Introduction to C++ & IDE Setup'],
            [$cs102, $tNadia, $progCS, 1, '2024-09-11', '09:00', '10:00', 'Variables, Data Types, and Operators'],
            [$cs102, $tNadia, $progCS, 1, '2024-09-16', '09:00', '10:00', 'if-else and switch Statements'],
            [$cs102, $tNadia, $progCS, 1, '2024-09-18', '09:00', '10:00', 'for, while, and do-while Loops'],
            [$cs102, $tNadia, $progCS, 1, '2024-09-23', '09:00', '10:00', 'Functions — Declaration and Definition'],
            [$cs102, $tNadia, $progCS, 1, '2024-09-25', '09:00', '10:00', 'Arrays — 1D and 2D'],
            [$cs102, $tNadia, $progCS, 1, '2024-09-30', '09:00', '10:00', 'Strings in C++ — Character Arrays'],

            // CS-301 sessions (Sem 3, CS)
            [$cs301, $tAsif, $progCS, 3, '2024-09-10', '10:00', '11:00', 'Introduction to DSA & Complexity'],
            [$cs301, $tAsif, $progCS, 3, '2024-09-12', '10:00', '11:00', 'Arrays vs Linked Lists'],
            [$cs301, $tAsif, $progCS, 3, '2024-09-17', '10:00', '11:00', 'Singly Linked List Operations'],
            [$cs301, $tAsif, $progCS, 3, '2024-09-19', '10:00', '11:00', 'Doubly and Circular Linked Lists'],
            [$cs301, $tAsif, $progCS, 3, '2024-09-24', '10:00', '11:00', 'Stack — Push, Pop, Peek Operations'],

            // CS-302 sessions (Sem 3, CS)
            [$cs302, $tBilal, $progCS, 3, '2024-09-10', '11:00', '12:00', 'Database Concepts & DBMS Overview'],
            [$cs302, $tBilal, $progCS, 3, '2024-09-12', '11:00', '12:00', 'Entity-Relationship Diagrams'],
            [$cs302, $tBilal, $progCS, 3, '2024-09-17', '11:00', '12:00', 'Relational Model & Keys'],
            [$cs302, $tBilal, $progCS, 3, '2024-09-19', '11:00', '12:00', 'SQL DDL: CREATE, ALTER, DROP'],
            [$cs302, $tBilal, $progCS, 3, '2024-09-24', '11:00', '12:00', 'SQL DML: INSERT, UPDATE, DELETE'],

            // CS-501 sessions (Sem 5, CS)
            [$cs501, $tAsif, $progCS, 5, '2024-09-09', '12:00', '13:00', 'Introduction to Artificial Intelligence'],
            [$cs501, $tAsif, $progCS, 5, '2024-09-11', '12:00', '13:00', 'Intelligent Agents & Environments'],
            [$cs501, $tAsif, $progCS, 5, '2024-09-16', '12:00', '13:00', 'Problem Solving & Search Algorithms'],

            // EDU-101 sessions (Sem 1, BEd)
            [$edu101, $tRukh, $progBEd, 1, '2024-09-09', '09:00', '10:00', 'Introduction to Education & its Aims'],
            [$edu101, $tRukh, $progBEd, 1, '2024-09-11', '09:00', '10:00', 'Formal vs Informal Education'],
            [$edu101, $tRukh, $progBEd, 1, '2024-09-16', '09:00', '10:00', 'History of Education in Pakistan'],
            [$edu101, $tRukh, $progBEd, 1, '2024-09-18', '09:00', '10:00', 'National Education Policy Overview'],

            // EDU-102 sessions (Sem 1, BEd)
            [$edu102, $tRukh, $progBEd, 1, '2024-09-10', '10:00', '11:00', 'Introduction to Educational Psychology'],
            [$edu102, $tRukh, $progBEd, 1, '2024-09-12', '10:00', '11:00', 'Behaviorist Learning Theory'],
        ];

        // Attendance patterns per course: roll_number => [P/A/L] per session index
        $csS1Students  = ['CS-2024-0001', 'CS-2024-0002', 'CS-2024-0003', 'CS-2024-0004', 'CS-2024-0005', 'CS-2024-0006', 'CS-2024-0007', 'CS-2024-0008'];
        $csS3Students  = ['CS-2023-0001', 'CS-2023-0002', 'CS-2023-0004', 'CS-2023-0005', 'CS-2023-0006'];
        $csS5Students  = ['CS-2022-0001', 'CS-2022-0002', 'CS-2022-0004', 'CS-2022-0005', 'CS-2022-0006'];
        $bedS1Students = ['EDU-2024-0001', 'EDU-2024-0002', 'EDU-2024-0003', 'EDU-2024-0004', 'EDU-2024-0005', 'EDU-2024-0006'];

        // Maps course_id → list of student roll numbers for that class
        $courseStudents = [
            $cs101  => $csS1Students,
            $cs102  => $csS1Students,
            $cs301  => $csS3Students,
            $cs302  => $csS3Students,
            $cs501  => $csS5Students,
            $edu101 => $bedS1Students,
            $edu102 => $bedS1Students,
        ];

        // Fixed attendance patterns: 0=Present, 1=Late, 2=Absent, 3=Leave
        // One row per student, one value per session of that course
        $patterns = [
            // CS S1 students (8): patterns for 5 CS-101, 7 CS-102 sessions
            'CS-2024-0001' => [0, 0, 0, 0, 0,   0, 0, 0, 0, 0, 0, 0,   0, 0, 0, 0, 0,   0, 0, 0, 0, 0,   0, 0, 0,   0, 0, 0, 0,   0, 0],
            'CS-2024-0002' => [0, 0, 0, 0, 0,   0, 0, 0, 0, 0, 0, 0,   0, 0, 0, 0, 0,   0, 0, 0, 0, 0,   0, 0, 0,   0, 0, 0, 0,   0, 0],
            'CS-2024-0003' => [0, 2, 0, 0, 0,   0, 1, 0, 0, 2, 0, 0,   0, 0, 2, 0, 0,   0, 0, 0, 2, 0,   0, 0, 0,   0, 0, 0, 0,   0, 0],
            'CS-2024-0004' => [0, 0, 0, 0, 0,   0, 0, 0, 0, 0, 0, 0,   0, 0, 0, 0, 0,   0, 0, 0, 0, 0,   0, 0, 0,   0, 0, 0, 0,   0, 0],
            'CS-2024-0005' => [0, 0, 2, 0, 0,   1, 0, 0, 2, 0, 0, 0,   0, 2, 0, 0, 0,   0, 0, 0, 0, 2,   0, 2, 0,   0, 0, 0, 0,   0, 0],
            'CS-2024-0006' => [0, 0, 0, 0, 0,   0, 0, 0, 0, 0, 0, 0,   0, 0, 0, 0, 0,   0, 0, 0, 0, 0,   0, 0, 0,   0, 0, 0, 0,   0, 0],
            'CS-2024-0007' => [0, 0, 0, 2, 0,   0, 0, 1, 0, 0, 2, 0,   0, 0, 0, 0, 2,   0, 0, 2, 0, 0,   0, 0, 2,   0, 0, 0, 0,   0, 0],
            'CS-2024-0008' => [0, 0, 0, 0, 0,   0, 0, 0, 0, 0, 0, 0,   0, 0, 0, 0, 0,   0, 0, 0, 0, 0,   0, 0, 0,   0, 0, 0, 0,   0, 0],
            // CS S3 students (5)
            'CS-2023-0001' => [0, 0, 0, 0, 0,   0, 0, 0, 0, 0, 0, 0,   0, 0, 0, 0, 0,   0, 0, 0, 0, 0,   0, 0, 0,   0, 0, 0, 0,   0, 0],
            'CS-2023-0002' => [0, 0, 0, 0, 0,   0, 0, 0, 0, 0, 0, 0,   0, 0, 0, 0, 0,   0, 0, 0, 0, 0,   0, 0, 0,   0, 0, 0, 0,   0, 0],
            'CS-2023-0004' => [0, 0, 2, 0, 0,   0, 0, 2, 0, 0, 0, 0,   0, 2, 0, 0, 0,   0, 0, 0, 2, 0,   0, 2, 0,   0, 0, 0, 0,   0, 0],
            'CS-2023-0005' => [0, 0, 0, 0, 0,   0, 0, 0, 0, 0, 0, 0,   0, 0, 0, 0, 0,   0, 0, 0, 0, 0,   0, 0, 0,   0, 0, 0, 0,   0, 0],
            'CS-2023-0006' => [0, 2, 0, 2, 0,   0, 1, 0, 2, 0, 0, 2,   2, 0, 0, 2, 0,   0, 2, 0, 0, 2,   0, 0, 2,   0, 0, 0, 0,   0, 0],
            // CS S5 students (5)
            'CS-2022-0001' => [0, 0, 0, 0, 0,   0, 0, 0, 0, 0, 0, 0,   0, 0, 0, 0, 0,   0, 0, 0, 0, 0,   0, 0, 0,   0, 0, 0, 0,   0, 0],
            'CS-2022-0002' => [0, 0, 0, 0, 0,   0, 0, 0, 0, 0, 0, 0,   0, 0, 0, 0, 0,   0, 0, 0, 0, 0,   0, 0, 0,   0, 0, 0, 0,   0, 0],
            'CS-2022-0004' => [0, 0, 0, 0, 0,   0, 0, 0, 0, 0, 0, 0,   0, 0, 0, 0, 0,   0, 0, 0, 0, 0,   0, 0, 0,   0, 0, 0, 0,   0, 0],
            'CS-2022-0005' => [0, 0, 0, 0, 0,   0, 0, 0, 0, 0, 0, 0,   0, 0, 0, 0, 0,   0, 0, 0, 0, 0,   0, 0, 0,   0, 0, 0, 0,   0, 0],
            'CS-2022-0006' => [0, 2, 0, 0, 2,   0, 0, 0, 2, 0, 0, 0,   0, 0, 2, 0, 0,   0, 0, 2, 0, 0,   2, 0, 0,   0, 0, 0, 0,   0, 0],
            // BEd S1 students (6)
            'EDU-2024-0001'=> [0, 0, 0, 0, 0,   0, 0, 0, 0, 0, 0, 0,   0, 0, 0, 0, 0,   0, 0, 0, 0, 0,   0, 0, 0,   0, 0, 0, 0,   0, 0],
            'EDU-2024-0002'=> [0, 0, 0, 0, 0,   0, 0, 0, 0, 0, 0, 0,   0, 0, 0, 0, 0,   0, 0, 0, 0, 0,   0, 0, 0,   0, 0, 0, 0,   0, 0],
            'EDU-2024-0003'=> [0, 0, 0, 0, 0,   0, 0, 0, 0, 0, 0, 0,   0, 0, 0, 0, 0,   0, 0, 0, 0, 0,   0, 0, 0,   0, 0, 0, 0,   0, 0],
            'EDU-2024-0004'=> [0, 2, 0, 0, 0,   0, 0, 0, 2, 0, 0, 0,   0, 0, 2, 0, 0,   0, 0, 0, 2, 0,   0, 2, 0,   2, 0, 0, 0,   2, 0],
            'EDU-2024-0005'=> [0, 0, 0, 0, 0,   0, 0, 0, 0, 0, 0, 0,   0, 0, 0, 0, 0,   0, 0, 0, 0, 0,   0, 0, 0,   0, 0, 0, 0,   0, 0],
            'EDU-2024-0006'=> [0, 0, 2, 0, 0,   0, 0, 0, 0, 2, 0, 0,   0, 2, 0, 0, 0,   0, 0, 0, 0, 0,   0, 0, 0,   0, 0, 2, 0,   0, 0],
        ];

        $statusMap = [0 => $P, 1 => $L, 2 => $Ab, 3 => $Lv];

        // Track session index per course so patterns align correctly
        $courseSessionIdx = [];

        foreach ($sessions as [$courseId, $teacherId, $progId, $semNum, $date, $start, $end, $topic]) {
            if (! $courseId || ! $teacherId) {
                continue;
            }

            $session = AttendanceSession::firstOrCreate(
                ['course_id' => $courseId, 'session_date' => $date],
                [
                    'course_id'           => $courseId,
                    'teacher_id'          => $teacherId,
                    'academic_program_id' => $progId,
                    'semester_number'     => $semNum,
                    'session_date'        => $date,
                    'start_time'          => $start,
                    'end_time'            => $end,
                    'topic_covered'       => $topic,
                    'is_locked'           => true,
                ]
            );

            $sessionStudents = $courseStudents[$courseId] ?? [];
            $idx = $courseSessionIdx[$courseId] ?? 0;
            $courseSessionIdx[$courseId] = $idx + 1;

            foreach ($sessionStudents as $roll) {
                $student = $students[$roll] ?? null;
                if (! $student) {
                    continue;
                }

                $pat     = $patterns[$roll] ?? [];
                $statusCode = $pat[$idx] ?? 0;
                $status  = $statusMap[$statusCode];

                AttendanceRecord::firstOrCreate(
                    ['attendance_session_id' => $session->id, 'student_id' => $student->id],
                    [
                        'attendance_session_id' => $session->id,
                        'student_id'            => $student->id,
                        'status'                => $status,
                    ]
                );
            }
        }
    }
}
