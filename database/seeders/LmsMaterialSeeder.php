<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\LmsMaterial;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class LmsMaterialSeeder extends Seeder
{
    public function run(): void
    {
        $courses  = Course::all()->keyBy('code');
        $teachers = Teacher::all()->keyBy('employee_id');

        $cs101  = $courses['CS-101']?->id;
        $cs102  = $courses['CS-102']?->id;
        $cs201  = $courses['CS-201']?->id;
        $cs301  = $courses['CS-301']?->id;
        $cs302  = $courses['CS-302']?->id;
        $cs501  = $courses['CS-501']?->id;
        $cs502  = $courses['CS-502']?->id;
        $eng101 = $courses['ENG-101']?->id;
        $edu101 = $courses['EDU-101']?->id;
        $edu102 = $courses['EDU-102']?->id;

        $tAsif   = $teachers['EMP-0001']?->id;
        $tNadia  = $teachers['EMP-0002']?->id;
        $tBilal  = $teachers['EMP-0003']?->id;
        $tFaisal = $teachers['EMP-0007']?->id;
        $tKamran = $teachers['EMP-0009']?->id;
        $tImran  = $teachers['EMP-0014']?->id;
        $tRukh   = $teachers['EMP-0004']?->id;
        $tAmna   = $teachers['EMP-0006']?->id;

        $materials = [
            // ─── CS-101 Introduction to Computing ─────────────────────────────────
            ['course' => $cs101, 'teacher' => $tNadia,  'title' => 'CS-101 Week 1: Introduction to Computers & Hardware',         'type' => 'slide',    'week' => 1,  'pub' => true],
            ['course' => $cs101, 'teacher' => $tNadia,  'title' => 'CS-101 Week 2: Operating Systems Overview',                   'type' => 'slide',    'week' => 2,  'pub' => true],
            ['course' => $cs101, 'teacher' => $tNadia,  'title' => 'CS-101 Week 3: Number Systems (Binary, Octal, Hex)',          'type' => 'document', 'week' => 3,  'pub' => true],
            ['course' => $cs101, 'teacher' => $tNadia,  'title' => 'CS-101 Lab Manual — Practical 1: MS Office Basics',           'type' => 'document', 'week' => 1,  'pub' => true],
            ['course' => $cs101, 'teacher' => $tNadia,  'title' => 'CS-101 Reference: Introduction to Computing — Video Lecture', 'type' => 'link',     'week' => 1,  'pub' => true,  'url' => 'https://www.youtube.com'],

            // ─── CS-102 Programming Fundamentals ──────────────────────────────────
            ['course' => $cs102, 'teacher' => $tNadia,  'title' => 'CS-102 Week 1: Variables, Data Types & Operators in C++',     'type' => 'slide',    'week' => 1,  'pub' => true],
            ['course' => $cs102, 'teacher' => $tNadia,  'title' => 'CS-102 Week 2: Control Structures (if, switch, loops)',       'type' => 'slide',    'week' => 2,  'pub' => true],
            ['course' => $cs102, 'teacher' => $tNadia,  'title' => 'CS-102 Week 3: Functions and Arrays',                        'type' => 'slide',    'week' => 3,  'pub' => true],
            ['course' => $cs102, 'teacher' => $tNadia,  'title' => 'CS-102 Week 4: Pointers and Memory Management',              'type' => 'document', 'week' => 4,  'pub' => true],
            ['course' => $cs102, 'teacher' => $tNadia,  'title' => 'CS-102 Lab Manual — Complete Lab Exercises 1-10',            'type' => 'document', 'week' => 1,  'pub' => true],
            ['course' => $cs102, 'teacher' => $tNadia,  'title' => 'CS-102 Midterm Syllabus & Sample Questions',                  'type' => 'document', 'week' => 7,  'pub' => true],

            // ─── CS-301 Data Structures & Algorithms ──────────────────────────────
            ['course' => $cs301, 'teacher' => $tAsif,   'title' => 'CS-301 Week 1: Arrays, Linked Lists & Complexity Analysis',  'type' => 'slide',    'week' => 1,  'pub' => true],
            ['course' => $cs301, 'teacher' => $tAsif,   'title' => 'CS-301 Week 2: Stacks and Queues',                           'type' => 'slide',    'week' => 2,  'pub' => true],
            ['course' => $cs301, 'teacher' => $tAsif,   'title' => 'CS-301 Week 3: Trees and Binary Search Trees',               'type' => 'slide',    'week' => 3,  'pub' => true],
            ['course' => $cs301, 'teacher' => $tAsif,   'title' => 'CS-301 Week 4: Sorting Algorithms — QuickSort, MergeSort',   'type' => 'slide',    'week' => 4,  'pub' => true],
            ['course' => $cs301, 'teacher' => $tAsif,   'title' => 'CS-301 Algorithm Complexity Cheat Sheet',                    'type' => 'document', 'week' => 1,  'pub' => true],

            // ─── CS-302 Database Systems ───────────────────────────────────────────
            ['course' => $cs302, 'teacher' => $tBilal,  'title' => 'CS-302 Week 1: ER Diagrams & Database Design',               'type' => 'slide',    'week' => 1,  'pub' => true],
            ['course' => $cs302, 'teacher' => $tBilal,  'title' => 'CS-302 Week 2: SQL Basics — DDL and DML',                    'type' => 'slide',    'week' => 2,  'pub' => true],
            ['course' => $cs302, 'teacher' => $tBilal,  'title' => 'CS-302 Week 3: Normalization (1NF, 2NF, 3NF)',               'type' => 'slide',    'week' => 3,  'pub' => true],
            ['course' => $cs302, 'teacher' => $tBilal,  'title' => 'CS-302 Week 4: Joins, Views and Stored Procedures',          'type' => 'slide',    'week' => 4,  'pub' => true],
            ['course' => $cs302, 'teacher' => $tBilal,  'title' => 'CS-302 Lab Guide: MySQL Installation & Practice Exercises',  'type' => 'document', 'week' => 1,  'pub' => true],
            ['course' => $cs302, 'teacher' => $tBilal,  'title' => 'CS-302 Sample Database: College Management System',          'type' => 'document', 'week' => 3,  'pub' => true],

            // ─── CS-501 Artificial Intelligence ───────────────────────────────────
            ['course' => $cs501, 'teacher' => $tAsif,   'title' => 'CS-501 Week 1: Introduction to AI — History & Applications', 'type' => 'slide',    'week' => 1,  'pub' => true],
            ['course' => $cs501, 'teacher' => $tAsif,   'title' => 'CS-501 Week 2: Search Algorithms — BFS, DFS, A*',            'type' => 'slide',    'week' => 2,  'pub' => true],
            ['course' => $cs501, 'teacher' => $tAsif,   'title' => 'CS-501 Week 3: Machine Learning Fundamentals',               'type' => 'document', 'week' => 3,  'pub' => true],
            ['course' => $cs501, 'teacher' => $tAsif,   'title' => 'CS-501 Research Paper: Recent Advances in Deep Learning',    'type' => 'document', 'week' => 5,  'pub' => true],

            // ─── CS-502 Web Technologies ───────────────────────────────────────────
            ['course' => $cs502, 'teacher' => $tKamran, 'title' => 'CS-502 Week 1: HTML5 & CSS3 Fundamentals',                   'type' => 'slide',    'week' => 1,  'pub' => true],
            ['course' => $cs502, 'teacher' => $tKamran, 'title' => 'CS-502 Week 2: JavaScript & DOM Manipulation',               'type' => 'slide',    'week' => 2,  'pub' => true],
            ['course' => $cs502, 'teacher' => $tKamran, 'title' => 'CS-502 Week 3: PHP & MySQL Integration',                     'type' => 'document', 'week' => 3,  'pub' => false],
            ['course' => $cs502, 'teacher' => $tKamran, 'title' => 'CS-502 Week 4: Introduction to Laravel Framework',           'type' => 'slide',    'week' => 4,  'pub' => true],

            // ─── EDU-101 Foundations of Education ─────────────────────────────────
            ['course' => $edu101, 'teacher' => $tRukh,  'title' => 'EDU-101 Week 1: Concept and Aims of Education',              'type' => 'slide',    'week' => 1,  'pub' => true],
            ['course' => $edu101, 'teacher' => $tRukh,  'title' => 'EDU-101 Week 2: Historical Perspectives on Education',       'type' => 'document', 'week' => 2,  'pub' => true],
            ['course' => $edu101, 'teacher' => $tRukh,  'title' => 'EDU-101 Week 3: Philosophy of Education in Pakistan',        'type' => 'slide',    'week' => 3,  'pub' => true],

            // ─── EDU-102 Educational Psychology ───────────────────────────────────
            ['course' => $edu102, 'teacher' => $tRukh,  'title' => 'EDU-102 Week 1: Learning Theories — Behaviourism',           'type' => 'slide',    'week' => 1,  'pub' => true],
            ['course' => $edu102, 'teacher' => $tRukh,  'title' => 'EDU-102 Week 2: Cognitive Development (Piaget)',             'type' => 'document', 'week' => 2,  'pub' => true],

            // ─── ENG-101 English Composition ──────────────────────────────────────
            ['course' => $eng101, 'teacher' => $tAmna,  'title' => 'ENG-101 Week 1: Paragraph Writing Techniques',               'type' => 'slide',    'week' => 1,  'pub' => true],
            ['course' => $eng101, 'teacher' => $tAmna,  'title' => 'ENG-101 Week 2: Essay Types and Structure',                  'type' => 'document', 'week' => 2,  'pub' => true],
        ];

        foreach ($materials as $m) {
            if (! $m['course'] || ! $m['teacher']) {
                continue;
            }

            LmsMaterial::firstOrCreate(
                ['course_id' => $m['course'], 'title' => $m['title']],
                [
                    'course_id'    => $m['course'],
                    'teacher_id'   => $m['teacher'],
                    'title'        => $m['title'],
                    'material_type'=> $m['type'],
                    'external_url' => $m['url'] ?? null,
                    'week_number'  => $m['week'],
                    'is_published' => $m['pub'],
                    'download_count' => 0,
                ]
            );
        }
    }
}
