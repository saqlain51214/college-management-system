<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\LmsAssignment;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class LmsAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $courses  = Course::all()->keyBy('code');
        $teachers = Teacher::all()->keyBy('employee_id');

        $cs101  = $courses['CS-101']?->id;
        $cs102  = $courses['CS-102']?->id;
        $cs301  = $courses['CS-301']?->id;
        $cs302  = $courses['CS-302']?->id;
        $cs501  = $courses['CS-501']?->id;
        $cs502  = $courses['CS-502']?->id;
        $edu101 = $courses['EDU-101']?->id;
        $edu102 = $courses['EDU-102']?->id;
        $eng101 = $courses['ENG-101']?->id;

        $tAsif   = $teachers['EMP-0001']?->id;
        $tNadia  = $teachers['EMP-0002']?->id;
        $tBilal  = $teachers['EMP-0003']?->id;
        $tKamran = $teachers['EMP-0009']?->id;
        $tRukh   = $teachers['EMP-0004']?->id;
        $tAmna   = $teachers['EMP-0006']?->id;

        $assignments = [
            // ─── CS-101 ─────────────────────────────────────────────────────────
            [
                'course' => $cs101, 'teacher' => $tNadia,
                'title'  => 'CS-101 Assignment 1: Binary Number Conversions',
                'desc'   => 'Convert 10 decimal numbers to binary, octal, and hexadecimal.',
                'instr'  => 'Show all working steps. Submit as PDF. No plagiarism.',
                'marks'  => 10, 'due' => '2024-10-20 23:59:00', 'sub_type' => 'file', 'late' => true, 'pub' => true,
            ],
            [
                'course' => $cs101, 'teacher' => $tNadia,
                'title'  => 'CS-101 Assignment 2: Hardware Components Report',
                'desc'   => 'Write a 2-page report comparing CPU, RAM, and storage technologies.',
                'instr'  => 'Use A4 size. Font size 12, Times New Roman. Include diagrams.',
                'marks'  => 15, 'due' => '2024-11-05 23:59:00', 'sub_type' => 'file', 'late' => false, 'pub' => true,
            ],

            // ─── CS-102 ─────────────────────────────────────────────────────────
            [
                'course' => $cs102, 'teacher' => $tNadia,
                'title'  => 'CS-102 Assignment 1: C++ Basics — Variables and Control Flow',
                'desc'   => 'Write 5 C++ programs: temperature converter, grade calculator, number palindrome, Fibonacci series, simple calculator.',
                'instr'  => 'Zip all .cpp files and submit. Include output screenshots.',
                'marks'  => 20, 'due' => '2024-10-15 23:59:00', 'sub_type' => 'file', 'late' => true, 'pub' => true,
            ],
            [
                'course' => $cs102, 'teacher' => $tNadia,
                'title'  => 'CS-102 Assignment 2: Functions and Arrays',
                'desc'   => 'Implement: (1) Array sorting functions, (2) Matrix multiplication, (3) String operations using char arrays.',
                'instr'  => 'Each program must have proper comments and function documentation.',
                'marks'  => 25, 'due' => '2024-11-01 23:59:00', 'sub_type' => 'file', 'late' => false, 'pub' => true,
            ],
            [
                'course' => $cs102, 'teacher' => $tNadia,
                'title'  => 'CS-102 Project: Console-Based Mini Library System',
                'desc'   => 'Build a console-based library management system in C++ supporting: add book, search book, issue/return book, and view all books.',
                'instr'  => 'Must use arrays, functions, and structs. Submit zip file with source code and 5-minute demo video.',
                'marks'  => 50, 'due' => '2025-01-05 23:59:00', 'sub_type' => 'file', 'late' => false, 'pub' => true,
            ],

            // ─── CS-301 ─────────────────────────────────────────────────────────
            [
                'course' => $cs301, 'teacher' => $tAsif,
                'title'  => 'CS-301 Assignment 1: Linked List Operations',
                'desc'   => 'Implement singly and doubly linked lists with: insert, delete, search, and reverse operations.',
                'instr'  => 'Use C++ with proper time complexity analysis for each operation.',
                'marks'  => 20, 'due' => '2024-10-10 23:59:00', 'sub_type' => 'file', 'late' => true, 'pub' => true,
            ],
            [
                'course' => $cs301, 'teacher' => $tAsif,
                'title'  => 'CS-301 Assignment 2: BST Implementation & Traversals',
                'desc'   => 'Implement a Binary Search Tree with in-order, pre-order, and post-order traversals. Include insert, delete, and search.',
                'instr'  => 'Provide test cases with expected outputs. Include complexity analysis.',
                'marks'  => 25, 'due' => '2024-11-10 23:59:00', 'sub_type' => 'file', 'late' => false, 'pub' => true,
            ],

            // ─── CS-302 ─────────────────────────────────────────────────────────
            [
                'course' => $cs302, 'teacher' => $tBilal,
                'title'  => 'CS-302 Assignment 1: ER Diagram Design',
                'desc'   => 'Design a complete ER diagram for a Hospital Management System. Include entities, attributes, and all relationships.',
                'instr'  => 'Draw using Draw.io or Lucidchart. Export as PDF.',
                'marks'  => 15, 'due' => '2024-10-08 23:59:00', 'sub_type' => 'file', 'late' => true, 'pub' => true,
            ],
            [
                'course' => $cs302, 'teacher' => $tBilal,
                'title'  => 'CS-302 Lab Assignment: SQL Queries Practice',
                'desc'   => 'Write SQL queries for the given College DB schema: 20 SELECT queries (including joins, subqueries, aggregates), 5 INSERT, 3 UPDATE, 2 DELETE.',
                'instr'  => 'Submit .sql file. Must be executable on MySQL Workbench. Attach screenshots of results.',
                'marks'  => 30, 'due' => '2024-11-20 23:59:00', 'sub_type' => 'file', 'late' => false, 'pub' => true,
            ],

            // ─── CS-501 AI ──────────────────────────────────────────────────────
            [
                'course' => $cs501, 'teacher' => $tAsif,
                'title'  => 'CS-501 Assignment 1: Implementing BFS and DFS',
                'desc'   => 'Implement BFS and DFS for a graph. Compare time and space complexity with analysis.',
                'instr'  => 'Use Python or C++. Provide sample input/output. Complexity analysis mandatory.',
                'marks'  => 20, 'due' => '2024-10-25 23:59:00', 'sub_type' => 'file', 'late' => true, 'pub' => true,
            ],
            [
                'course' => $cs501, 'teacher' => $tAsif,
                'title'  => 'CS-501 Research Review: AI Applications in Healthcare',
                'desc'   => 'Read 3 research papers on AI in healthcare. Write a 3-page comparative review.',
                'instr'  => 'Follow IEEE citation format. Submit as PDF. No AI-generated text.',
                'marks'  => 25, 'due' => '2024-12-01 23:59:00', 'sub_type' => 'file', 'late' => false, 'pub' => true,
            ],

            // ─── CS-502 Web Technologies ────────────────────────────────────────
            [
                'course' => $cs502, 'teacher' => $tKamran,
                'title'  => 'CS-502 Assignment 1: Personal Portfolio Website',
                'desc'   => 'Build a 5-page personal portfolio website using HTML5, CSS3, and JavaScript. Must be responsive.',
                'instr'  => 'Upload to GitHub Pages. Submit GitHub link. Include: Home, About, Skills, Projects, Contact pages.',
                'marks'  => 30, 'due' => '2024-11-15 23:59:00', 'sub_type' => 'link', 'late' => false, 'pub' => true,
            ],

            // ─── EDU-101 ─────────────────────────────────────────────────────────
            [
                'course' => $edu101, 'teacher' => $tRukh,
                'title'  => 'EDU-101 Assignment: Essay on Education System in Pakistan',
                'desc'   => 'Write a 1500-word essay critically analyzing the current education system of Pakistan and suggesting improvements.',
                'instr'  => 'Typed on A4, 12pt font. Minimum 5 references (APA format).',
                'marks'  => 20, 'due' => '2024-10-25 23:59:00', 'sub_type' => 'file', 'late' => true, 'pub' => true,
            ],

            // ─── ENG-101 ─────────────────────────────────────────────────────────
            [
                'course' => $eng101, 'teacher' => $tAmna,
                'title'  => 'ENG-101 Assignment: Argumentative Essay',
                'desc'   => 'Write a 500-word argumentative essay on: "Social media does more harm than good."',
                'instr'  => 'Must include thesis statement, body paragraphs with evidence, and a conclusion. Handwritten or typed.',
                'marks'  => 15, 'due' => '2024-10-18 23:59:00', 'sub_type' => 'file', 'late' => true, 'pub' => true,
            ],
        ];

        foreach ($assignments as $a) {
            if (! $a['course'] || ! $a['teacher']) {
                continue;
            }

            LmsAssignment::firstOrCreate(
                ['course_id' => $a['course'], 'title' => $a['title']],
                [
                    'course_id'            => $a['course'],
                    'teacher_id'           => $a['teacher'],
                    'title'                => $a['title'],
                    'description'          => $a['desc'],
                    'instructions'         => $a['instr'],
                    'total_marks'          => $a['marks'],
                    'due_datetime'         => $a['due'],
                    'submission_type'      => $a['sub_type'],
                    'allow_late_submission'=> $a['late'],
                    'is_published'         => $a['pub'],
                ]
            );
        }
    }
}
