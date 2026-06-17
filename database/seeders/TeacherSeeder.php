<?php

namespace Database\Seeders;

use App\Enums\EmploymentTypeEnum;
use App\Enums\GenderEnum;
use App\Enums\TeacherStatusEnum;
use App\Models\Department;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        $deptCS   = Department::where('slug', 'department-of-computer-science')->value('id');
        $deptEdu  = Department::where('slug', 'department-of-education')->value('id');
        $deptEng  = Department::where('slug', 'department-of-english')->value('id');
        $deptPhy  = Department::where('code', 'DEPT-PHY')->value('id');
        $deptIsl  = Department::where('code', 'DEPT-ISL')->value('id');

        $M  = GenderEnum::Male->value;
        $F  = GenderEnum::Female->value;
        $P  = EmploymentTypeEnum::Permanent->value;
        $V  = EmploymentTypeEnum::Visiting->value;
        $C  = EmploymentTypeEnum::Contractual->value;
        $A  = TeacherStatusEnum::Active->value;

        $teachers = [
            ['id' => 'EMP-0001', 'dept' => $deptCS,  'name' => 'Dr. Muhammad Asif Iqbal', 'father' => 'Haji Iqbal Ahmed',    'gender' => $M, 'cnic' => '35201-1111001-1', 'email' => 'dr.asif@college.edu.pk',      'phone' => '0300-1110001', 'city' => 'Lahore',      'qual' => 'PhD',       'spec' => 'Artificial Intelligence',    'inst' => 'University of Engineering & Technology, Lahore', 'yr' => 2015, 'desig' => 'Associate Professor',  'emp' => $P, 'exp' => 14, 'join' => '2011-08-15', 'grade' => 'BPS-19', 'salary' => 120000.00, 'status' => $A],
            ['id' => 'EMP-0002', 'dept' => $deptCS,  'name' => 'Ms. Nadia Zafar',          'father' => 'Zafar Ullah Khan',    'gender' => $F, 'cnic' => '35202-2222002-4', 'email' => 'nadia.zafar@college.edu.pk',  'phone' => '0321-2220002', 'city' => 'Lahore',      'qual' => 'MS/MPhil', 'spec' => 'Software Engineering',       'inst' => 'COMSATS University Islamabad',                   'yr' => 2018, 'desig' => 'Lecturer',             'emp' => $P, 'exp' => 6,  'join' => '2019-09-01', 'grade' => 'BPS-17', 'salary' => 65000.00,  'status' => $A],
            ['id' => 'EMP-0003', 'dept' => $deptCS,  'name' => 'Mr. Bilal Mehmood',        'father' => 'Abdul Mehmood',       'gender' => $M, 'cnic' => '35303-3333003-7', 'email' => 'bilal.mehmood@college.edu.pk','phone' => '0333-3330003', 'city' => 'Lahore',      'qual' => 'BS',       'spec' => 'Database Systems',            'inst' => 'Punjab University',                              'yr' => 2020, 'desig' => 'Visiting Lecturer',    'emp' => $V, 'exp' => 4,  'join' => '2021-01-10', 'grade' => null,      'salary' => 35000.00,  'status' => $A],
            ['id' => 'EMP-0004', 'dept' => $deptEdu, 'name' => 'Dr. Rukhsana Bibi',        'father' => 'Ghulam Nabi',         'gender' => $F, 'cnic' => '35201-4444004-2', 'email' => 'dr.rukhsana@college.edu.pk',  'phone' => '0311-4440004', 'city' => 'Lahore',      'qual' => 'PhD',       'spec' => 'Educational Psychology',     'inst' => 'Allama Iqbal Open University',                   'yr' => 2012, 'desig' => 'Assistant Professor',  'emp' => $P, 'exp' => 18, 'join' => '2007-03-20', 'grade' => 'BPS-18', 'salary' => 90000.00,  'status' => $A],
            ['id' => 'EMP-0005', 'dept' => $deptEdu, 'name' => 'Mr. Tariq Javaid',         'father' => 'Javaid Akhtar',       'gender' => $M, 'cnic' => '35202-5555005-9', 'email' => 'tariq.javaid@college.edu.pk', 'phone' => '0345-5550005', 'city' => 'Sheikhupura','qual' => 'MEd',       'spec' => 'Curriculum & Instruction',   'inst' => 'University of Education, Lahore',                'yr' => 2016, 'desig' => 'Lecturer',             'emp' => $P, 'exp' => 9,  'join' => '2016-09-01', 'grade' => 'BPS-17', 'salary' => 60000.00,  'status' => $A],
            ['id' => 'EMP-0006', 'dept' => $deptEng, 'name' => 'Ms. Amna Malik',           'father' => 'Malik Riaz',          'gender' => $F, 'cnic' => '35201-6666006-6', 'email' => 'amna.malik@college.edu.pk',   'phone' => '0301-6660006', 'city' => 'Lahore',      'qual' => 'MA/MSc',   'spec' => 'English Literature',         'inst' => 'Kinnaird College, Lahore',                       'yr' => 2017, 'desig' => 'Lecturer',             'emp' => $P, 'exp' => 7,  'join' => '2018-02-01', 'grade' => 'BPS-17', 'salary' => 58000.00,  'status' => $A],
            ['id' => 'EMP-0007', 'dept' => $deptCS,  'name' => 'Mr. Faisal Siddiqui',      'father' => 'Siddiq ur Rehman',    'gender' => $M, 'cnic' => '35202-7777007-3', 'email' => 'faisal.s@college.edu.pk',     'phone' => '0322-7770007', 'city' => 'Gujranwala', 'qual' => 'MS/MPhil', 'spec' => 'Computer Networks',          'inst' => 'FAST NUCES, Lahore',                             'yr' => 2019, 'desig' => 'Senior Lecturer',      'emp' => $C, 'exp' => 5,  'join' => '2020-08-10', 'grade' => 'BPS-17', 'salary' => 70000.00,  'status' => $A],
            ['id' => 'EMP-0008', 'dept' => $deptEdu, 'name' => 'Ms. Sobia Khalid',         'father' => 'Khalid Mehmood',      'gender' => $F, 'cnic' => '35201-8888008-0', 'email' => 'sobia.k@college.edu.pk',      'phone' => '0314-8880008', 'city' => 'Lahore',      'qual' => 'MS/MPhil', 'spec' => 'Special Education',          'inst' => null,                                             'yr' => null, 'desig' => 'Assistant Professor',  'emp' => $P, 'exp' => 11, 'join' => '2014-06-01', 'grade' => 'BPS-18', 'salary' => 85000.00,  'status' => TeacherStatusEnum::OnLeave->value, 'remarks' => 'Maternity leave till September 2026.'],
            ['id' => 'EMP-0009', 'dept' => $deptCS,  'name' => 'Mr. Kamran Gill',          'father' => 'Gill Ahmad',          'gender' => $M, 'cnic' => '35202-9009009-7', 'email' => 'kamran.gill@college.edu.pk',  'phone' => '0300-9000009', 'city' => 'Lahore',      'qual' => 'MS/MPhil', 'spec' => 'Web Technologies',           'inst' => 'LUMS Lahore',                                    'yr' => 2021, 'desig' => 'Lecturer',             'emp' => $P, 'exp' => 3,  'join' => '2022-09-01', 'grade' => 'BPS-17', 'salary' => 62000.00,  'status' => $A],
            ['id' => 'EMP-0010', 'dept' => $deptCS,  'name' => 'Ms. Areeba Khan',          'father' => 'Shahid Khan',         'gender' => $F, 'cnic' => '35201-1110010-4', 'email' => 'areeba.khan@college.edu.pk',  'phone' => '0321-1000010', 'city' => 'Lahore',      'qual' => 'BS',       'spec' => 'Programming & Algorithms',   'inst' => 'FAST NUCES, Lahore',                             'yr' => 2022, 'desig' => 'Visiting Lecturer',    'emp' => $V, 'exp' => 2,  'join' => '2023-02-15', 'grade' => null,      'salary' => 32000.00,  'status' => $A],
            ['id' => 'EMP-0011', 'dept' => $deptEdu, 'name' => 'Dr. Nasir Mehmood',        'father' => 'Mehmood Ahmad',       'gender' => $M, 'cnic' => '35303-2220011-1', 'email' => 'dr.nasir@college.edu.pk',     'phone' => '0333-2000011', 'city' => 'Lahore',      'qual' => 'PhD',       'spec' => 'Research Methodology',       'inst' => 'University of Education, Lahore',                'yr' => 2010, 'desig' => 'Professor',            'emp' => $P, 'exp' => 20, 'join' => '2005-08-01', 'grade' => 'BPS-20', 'salary' => 145000.00, 'status' => $A],
            ['id' => 'EMP-0012', 'dept' => $deptPhy, 'name' => 'Mr. Zulfiqar Ali',         'father' => 'Ali Nawaz',           'gender' => $M, 'cnic' => '35201-3330012-8', 'email' => 'zulfiqar.ali@college.edu.pk', 'phone' => '0311-3000012', 'city' => 'Lahore',      'qual' => 'MS/MPhil', 'spec' => 'Applied Mathematics',        'inst' => 'GC University Lahore',                           'yr' => 2014, 'desig' => 'Lecturer',             'emp' => $P, 'exp' => 10, 'join' => '2015-03-01', 'grade' => 'BPS-17', 'salary' => 63000.00,  'status' => $A],
            ['id' => 'EMP-0013', 'dept' => $deptEng, 'name' => 'Ms. Bushra Ahmed',         'father' => 'Ahmed Iqbal',         'gender' => $F, 'cnic' => '35202-4440013-5', 'email' => 'bushra.ahmed@college.edu.pk', 'phone' => '0322-4000013', 'city' => 'Lahore',      'qual' => 'MA/MSc',   'spec' => 'Applied Linguistics',        'inst' => 'University of the Punjab',                       'yr' => 2016, 'desig' => 'Lecturer',             'emp' => $P, 'exp' => 8,  'join' => '2017-08-20', 'grade' => 'BPS-17', 'salary' => 59000.00,  'status' => $A],
            ['id' => 'EMP-0014', 'dept' => $deptCS,  'name' => 'Mr. Imran Rafiq',          'father' => 'Rafiq Muhammad',      'gender' => $M, 'cnic' => '35303-5550014-2', 'email' => 'imran.rafiq@college.edu.pk',  'phone' => '0345-5000014', 'city' => 'Gujranwala', 'qual' => 'MS/MPhil', 'spec' => 'Cybersecurity',               'inst' => 'UET Taxila',                                     'yr' => 2020, 'desig' => 'Lecturer',             'emp' => $C, 'exp' => 4,  'join' => '2021-09-01', 'grade' => 'BPS-17', 'salary' => 61000.00,  'status' => $A],
            ['id' => 'EMP-0015', 'dept' => $deptIsl, 'name' => 'Ms. Fareeha Naz',          'father' => 'Naz Ahmad',           'gender' => $F, 'cnic' => '35201-6660015-9', 'email' => 'fareeha.naz@college.edu.pk',  'phone' => '0301-6000015', 'city' => 'Lahore',      'qual' => 'MA/MSc',   'spec' => 'Islamic Studies',            'inst' => 'International Islamic University, Islamabad',    'yr' => 2015, 'desig' => 'Lecturer',             'emp' => $P, 'exp' => 8,  'join' => '2017-01-15', 'grade' => 'BPS-17', 'salary' => 57000.00,  'status' => $A],
        ];

        foreach ($teachers as $d) {
            Teacher::firstOrCreate(
                ['employee_id' => $d['id']],
                [
                    'employee_id'               => $d['id'],
                    'department_id'             => $d['dept'],
                    'name'                      => $d['name'],
                    'father_name'               => $d['father'],
                    'gender'                    => $d['gender'],
                    'cnic'                      => $d['cnic'],
                    'email'                     => $d['email'],
                    'phone'                     => $d['phone'],
                    'city'                      => $d['city'],
                    'province'                  => 'Punjab',
                    'highest_qualification'     => $d['qual'],
                    'specialization'            => $d['spec'],
                    'qualification_institution' => $d['inst'],
                    'qualification_year'        => $d['yr'],
                    'designation'               => $d['desig'],
                    'employment_type'           => $d['emp'],
                    'experience_years'          => $d['exp'],
                    'joining_date'              => $d['join'],
                    'salary_grade'              => $d['grade'],
                    'basic_salary'              => $d['salary'],
                    'status'                    => $d['status'],
                    'is_active'                 => $d['status'] === $A,
                    'remarks'                   => $d['remarks'] ?? null,
                    'nationality'               => 'Pakistani',
                ]
            );
        }
    }
}
