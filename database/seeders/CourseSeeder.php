<?php

namespace Database\Seeders;

use App\Enums\CourseTypeEnum;
use App\Models\AcademicProgram;
use App\Models\Course;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $cs   = AcademicProgram::where('code', 'BS-CS')->value('id');
        $bed  = AcademicProgram::where('code', 'BED')->value('id');
        $dcs  = Department::where('slug', 'department-of-computer-science')->value('id');
        $deng = Department::where('slug', 'department-of-english')->value('id');
        $dedu = Department::where('slug', 'department-of-education')->value('id');

        $courses = [
            // BS CS — Semester 1
            ['name' => 'Introduction to Computing',       'code' => 'CS-101',   'type' => CourseTypeEnum::Core,     'sem' => 1, 'cr' => 3, 'th' => 3, 'lb' => 0,   'prog' => $cs,  'dept' => $dcs],
            ['name' => 'Programming Fundamentals',        'code' => 'CS-102',   'type' => CourseTypeEnum::Core,     'sem' => 1, 'cr' => 4, 'th' => 3, 'lb' => 1,   'prog' => $cs,  'dept' => $dcs],
            ['name' => 'Calculus and Analytical Geometry','code' => 'MATH-101', 'type' => CourseTypeEnum::Core,     'sem' => 1, 'cr' => 3, 'th' => 3, 'lb' => 0,   'prog' => $cs,  'dept' => $dcs],
            ['name' => 'English Composition and Comprehension','code' => 'ENG-101','type' => CourseTypeEnum::Core,  'sem' => 1, 'cr' => 3, 'th' => 3, 'lb' => 0,   'prog' => $cs,  'dept' => $deng],
            ['name' => 'Islamic Studies / Ethics',        'code' => 'ISL-101',  'type' => CourseTypeEnum::Core,     'sem' => 1, 'cr' => 2, 'th' => 2, 'lb' => 0,   'prog' => $cs,  'dept' => $dcs],

            // BS CS — Semester 2
            ['name' => 'Object Oriented Programming',     'code' => 'CS-201',   'type' => CourseTypeEnum::Core,     'sem' => 2, 'cr' => 4, 'th' => 3, 'lb' => 1,   'prog' => $cs,  'dept' => $dcs],
            ['name' => 'Discrete Structures',             'code' => 'MATH-201', 'type' => CourseTypeEnum::Core,     'sem' => 2, 'cr' => 3, 'th' => 3, 'lb' => 0,   'prog' => $cs,  'dept' => $dcs],
            ['name' => 'Digital Logic Design',            'code' => 'CS-202',   'type' => CourseTypeEnum::Core,     'sem' => 2, 'cr' => 4, 'th' => 3, 'lb' => 1,   'prog' => $cs,  'dept' => $dcs],

            // BS CS — Semester 3
            ['name' => 'Data Structures and Algorithms',  'code' => 'CS-301',   'type' => CourseTypeEnum::Core,     'sem' => 3, 'cr' => 4, 'th' => 3, 'lb' => 1,   'prog' => $cs,  'dept' => $dcs],
            ['name' => 'Database Systems',                'code' => 'CS-302',   'type' => CourseTypeEnum::Core,     'sem' => 3, 'cr' => 4, 'th' => 3, 'lb' => 1,   'prog' => $cs,  'dept' => $dcs],
            ['name' => 'Software Engineering',            'code' => 'CS-303',   'type' => CourseTypeEnum::Core,     'sem' => 4, 'cr' => 3, 'th' => 3, 'lb' => 0,   'prog' => $cs,  'dept' => $dcs],

            // BS CS — Advanced
            ['name' => 'Artificial Intelligence',         'code' => 'CS-501',   'type' => CourseTypeEnum::Core,     'sem' => 5, 'cr' => 3, 'th' => 3, 'lb' => 0,   'prog' => $cs,  'dept' => $dcs],
            ['name' => 'Web Technologies',                'code' => 'CS-502',   'type' => CourseTypeEnum::Elective, 'sem' => 5, 'cr' => 3, 'th' => 2, 'lb' => 1,   'prog' => $cs,  'dept' => $dcs],
            ['name' => 'Final Year Project I',            'code' => 'CS-701',   'type' => CourseTypeEnum::Project,  'sem' => 7, 'cr' => 3, 'th' => 0, 'lb' => 3,   'prog' => $cs,  'dept' => $dcs],
            ['name' => 'Final Year Project II',           'code' => 'CS-801',   'type' => CourseTypeEnum::Project,  'sem' => 8, 'cr' => 3, 'th' => 0, 'lb' => 3,   'prog' => $cs,  'dept' => $dcs, 'pre' => 'CS-701'],

            // B.Ed courses
            ['name' => 'Foundations of Education',        'code' => 'EDU-101',  'type' => CourseTypeEnum::Core,     'sem' => 1, 'cr' => 3, 'th' => 3, 'lb' => 0,   'prog' => $bed, 'dept' => $dedu],
            ['name' => 'Educational Psychology',          'code' => 'EDU-102',  'type' => CourseTypeEnum::Core,     'sem' => 1, 'cr' => 3, 'th' => 3, 'lb' => 0,   'prog' => $bed, 'dept' => $dedu],
            ['name' => 'Classroom Management',            'code' => 'EDU-201',  'type' => CourseTypeEnum::Core,     'sem' => 2, 'cr' => 3, 'th' => 3, 'lb' => 0,   'prog' => $bed, 'dept' => $dedu],
            ['name' => 'Teaching Practice (Internship)',  'code' => 'EDU-801',  'type' => CourseTypeEnum::Internship,'sem' => 8, 'cr' => 6, 'th' => 0, 'lb' => 6,  'prog' => $bed, 'dept' => $dedu],
        ];

        foreach ($courses as $c) {
            Course::firstOrCreate(
                ['code' => $c['code']],
                [
                    'department_id'      => $c['dept'],
                    'academic_program_id'=> $c['prog'],
                    'name'               => $c['name'],
                    'code'               => $c['code'],
                    'slug'               => Str::slug($c['name']),
                    'course_type'        => $c['type']->value,
                    'semester_number'    => $c['sem'],
                    'credit_hours'       => $c['cr'],
                    'theory_hours'       => $c['th'] ?: null,
                    'lab_hours'          => $c['lb'] ?: null,
                    'pre_requisites'     => $c['pre'] ?? null,
                    'is_active'          => true,
                    'show_on_website'    => false,
                    'sort_order'         => ($c['sem'] * 10),
                ]
            );
        }
    }
}
