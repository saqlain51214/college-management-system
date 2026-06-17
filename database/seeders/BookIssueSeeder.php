<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BookIssue;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class BookIssueSeeder extends Seeder
{
    public function run(): void
    {
        $students = Student::where('is_active', true)->get()->keyBy('roll_number');
        $teachers = Teacher::where('is_active', true)->get()->keyBy('employee_id');
        $books    = Book::all()->keyBy('accession_number');

        $issues = [
            // Student issues — current (not returned)
            ['book' => 'LIB-2024-001', 'roll' => 'CS-2023-0001', 'teacher' => null, 'issue' => '2024-10-05', 'due' => '2024-10-19', 'return' => null,         'cond_i' => 'good',     'cond_r' => null,  'fine' => 0,    'fine_paid' => false, 'by' => 'Librarian'],
            ['book' => 'LIB-2024-002', 'roll' => 'CS-2024-0002', 'teacher' => null, 'issue' => '2024-10-10', 'due' => '2024-10-24', 'return' => null,         'cond_i' => 'good',     'cond_r' => null,  'fine' => 0,    'fine_paid' => false, 'by' => 'Librarian'],
            ['book' => 'LIB-2024-003', 'roll' => 'CS-2022-0001', 'teacher' => null, 'issue' => '2024-10-08', 'due' => '2024-10-22', 'return' => null,         'cond_i' => 'good',     'cond_r' => null,  'fine' => 0,    'fine_paid' => false, 'by' => 'Librarian'],
            ['book' => 'LIB-2024-005', 'roll' => 'CS-2023-0002', 'teacher' => null, 'issue' => '2024-09-30', 'due' => '2024-10-14', 'return' => null,         'cond_i' => 'good',     'cond_r' => null,  'fine' => 150,  'fine_paid' => false, 'by' => 'Librarian', 'remarks' => 'Overdue — student notified.'],
            ['book' => 'LIB-2024-006', 'roll' => 'EDU-2024-0001','teacher' => null, 'issue' => '2024-10-12', 'due' => '2024-10-26', 'return' => null,         'cond_i' => 'good',     'cond_r' => null,  'fine' => 0,    'fine_paid' => false, 'by' => 'Librarian'],
            ['book' => 'LIB-2024-007', 'roll' => 'EDU-2024-0003','teacher' => null, 'issue' => '2024-10-01', 'due' => '2024-10-15', 'return' => null,         'cond_i' => 'good',     'cond_r' => null,  'fine' => 200,  'fine_paid' => false, 'by' => 'Librarian', 'remarks' => 'Overdue 14 days.'],

            // Student issues — returned
            ['book' => 'LIB-2024-004', 'roll' => 'CS-2024-0001', 'teacher' => null, 'issue' => '2024-09-15', 'due' => '2024-09-29', 'return' => '2024-09-27', 'cond_i' => 'good',     'cond_r' => 'good',  'fine' => 0, 'fine_paid' => false, 'by' => 'Librarian'],
            ['book' => 'LIB-2024-008', 'roll' => 'CS-2023-0004', 'teacher' => null, 'issue' => '2024-09-20', 'due' => '2024-10-04', 'return' => '2024-10-10', 'cond_i' => 'good',     'cond_r' => 'good',  'fine' => 60, 'fine_paid' => true,  'by' => 'Librarian', 'remarks' => 'Returned 6 days late. Fine Rs.60 collected.'],
            ['book' => 'LIB-2024-009', 'roll' => 'CS-2022-0005', 'teacher' => null, 'issue' => '2024-09-10', 'due' => '2024-09-24', 'return' => '2024-09-22', 'cond_i' => 'good',     'cond_r' => 'good',  'fine' => 0, 'fine_paid' => false, 'by' => 'Librarian'],
            ['book' => 'LIB-2024-010', 'roll' => 'EDU-2024-0002','teacher' => null, 'issue' => '2024-09-18', 'due' => '2024-10-02', 'return' => '2024-10-05', 'cond_i' => 'good',     'cond_r' => 'good',  'fine' => 30, 'fine_paid' => true,  'by' => 'Librarian', 'remarks' => 'Returned 3 days late.'],
            ['book' => 'LIB-2024-001', 'roll' => 'CS-2022-0002', 'teacher' => null, 'issue' => '2024-08-20', 'due' => '2024-09-03', 'return' => '2024-09-01', 'cond_i' => 'good',     'cond_r' => 'good',  'fine' => 0, 'fine_paid' => false, 'by' => 'Librarian'],
            ['book' => 'LIB-2024-002', 'roll' => 'CS-2023-0005', 'teacher' => null, 'issue' => '2024-08-25', 'due' => '2024-09-08', 'return' => '2024-09-06', 'cond_i' => 'good',     'cond_r' => 'good',  'fine' => 0, 'fine_paid' => false, 'by' => 'Librarian'],

            // Teacher issues
            ['book' => 'LIB-2024-003', 'roll' => null, 'teacher' => 'EMP-0001', 'issue' => '2024-10-01', 'due' => '2024-10-29', 'return' => null,         'cond_i' => 'good', 'cond_r' => null, 'fine' => 0, 'fine_paid' => false, 'by' => 'Librarian'],
            ['book' => 'LIB-2024-005', 'roll' => null, 'teacher' => 'EMP-0003', 'issue' => '2024-09-25', 'due' => '2024-10-23', 'return' => '2024-10-20', 'cond_i' => 'good', 'cond_r' => 'good', 'fine' => 0, 'fine_paid' => false, 'by' => 'Librarian'],
            ['book' => 'LIB-2024-006', 'roll' => null, 'teacher' => 'EMP-0004', 'issue' => '2024-10-05', 'due' => '2024-11-02', 'return' => null,         'cond_i' => 'good', 'cond_r' => null, 'fine' => 0, 'fine_paid' => false, 'by' => 'Librarian'],
            ['book' => 'LIB-2024-007', 'roll' => null, 'teacher' => 'EMP-0002', 'issue' => '2024-09-15', 'due' => '2024-10-13', 'return' => '2024-10-10', 'cond_i' => 'good', 'cond_r' => 'good', 'fine' => 0, 'fine_paid' => false, 'by' => 'Librarian'],
        ];

        foreach ($issues as $i) {
            $bookId    = $books[$i['book']]?->id ?? null;
            $studentId = $i['roll'] ? ($students[$i['roll']]?->id ?? null) : null;
            $teacherId = $i['teacher'] ? ($teachers[$i['teacher']]?->id ?? null) : null;

            if (! $bookId) {
                continue;
            }
            if (! $studentId && ! $teacherId) {
                continue;
            }

            BookIssue::firstOrCreate(
                [
                    'book_id'    => $bookId,
                    'student_id' => $studentId,
                    'teacher_id' => $teacherId,
                    'issue_date' => $i['issue'],
                ],
                [
                    'book_id'             => $bookId,
                    'student_id'          => $studentId,
                    'teacher_id'          => $teacherId,
                    'issue_date'          => $i['issue'],
                    'due_date'            => $i['due'],
                    'return_date'         => $i['return'],
                    'fine_amount'         => $i['fine'],
                    'fine_paid'           => $i['fine_paid'],
                    'condition_on_issue'  => $i['cond_i'],
                    'condition_on_return' => $i['cond_r'],
                    'issued_by'           => $i['by'],
                    'remarks'             => $i['remarks'] ?? null,
                ]
            );
        }
    }
}
