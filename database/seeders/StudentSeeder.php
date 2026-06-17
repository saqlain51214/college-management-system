<?php

namespace Database\Seeders;

use App\Enums\AdmissionTypeEnum;
use App\Enums\GenderEnum;
use App\Enums\StudentStatusEnum;
use App\Models\AcademicProgram;
use App\Models\AcademicYear;
use App\Models\Department;
use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $deptCS  = Department::where('slug', 'department-of-computer-science')->first();
        $deptEdu = Department::where('slug', 'department-of-education')->first();

        $progCS  = AcademicProgram::where('code', 'BS-CS')->first();
        $progBEd = AcademicProgram::where('code', 'BED')->first();

        $year2024 = AcademicYear::where('name', '2024-2025')->first();
        $year2023 = AcademicYear::where('name', '2023-2024')->first();
        $year2022 = AcademicYear::where('name', '2022-2023')->first();

        $M   = GenderEnum::Male->value;
        $F   = GenderEnum::Female->value;
        $A   = StudentStatusEnum::Active->value;
        $R   = AdmissionTypeEnum::Regular->value;
        $S   = AdmissionTypeEnum::Scholarship->value;
        $SF  = AdmissionTypeEnum::SelfFinance->value;
        $PUN = 'Punjab';

        $students = [
            // ─── BS CS — Batch 2024 (Semester 1) ───────────────────────────────────────
            ['roll' => 'CS-2024-0001', 'name' => 'Muhammad Ali Khan',    'father' => 'Arif Khan',           'gender' => $M, 'cnic' => '35202-1234001-1', 'phone' => '0333-2000001', 'email' => 'ali.khan@student.edu.pk',    'city' => 'Lahore',      'batch' => 2024, 'sem' => 1, 'adm' => $R,  'prev_q' => 'FSc', 'prev_m' => 895.00, 'prev_b' => 'BISE Lahore',     'prev_y' => 2024, 'status' => $A, 'blood' => 'B+', 'dept' => $deptCS,  'prog' => $progCS,  'year' => $year2024],
            ['roll' => 'CS-2024-0002', 'name' => 'Fatima Zahra',         'father' => 'Zafarullah Malik',    'gender' => $F, 'cnic' => '35201-2234002-4', 'phone' => '0333-2000002', 'email' => 'fatima.zahra@student.edu.pk','city' => 'Lahore',      'batch' => 2024, 'sem' => 1, 'adm' => $S,  'prev_q' => 'FSc', 'prev_m' => 980.00, 'prev_b' => 'BISE Lahore',     'prev_y' => 2024, 'status' => $A, 'blood' => 'A+', 'dept' => $deptCS,  'prog' => $progCS,  'year' => $year2024],
            ['roll' => 'CS-2024-0003', 'name' => 'Ahmad Raza Butt',      'father' => 'Muhammad Raza',       'gender' => $M, 'cnic' => '35201-3344003-5', 'phone' => '0312-3000003', 'email' => 'ahmad.raza@student.edu.pk',  'city' => 'Lahore',      'batch' => 2024, 'sem' => 1, 'adm' => $R,  'prev_q' => 'ICS', 'prev_m' => 840.00, 'prev_b' => 'BISE Lahore',     'prev_y' => 2024, 'status' => $A, 'blood' => 'O+', 'dept' => $deptCS,  'prog' => $progCS,  'year' => $year2024],
            ['roll' => 'CS-2024-0004', 'name' => 'Kiran Shahid',         'father' => 'Shahid Iqbal',        'gender' => $F, 'cnic' => '35202-4454004-8', 'phone' => '0321-4000004', 'email' => 'kiran.shahid@student.edu.pk','city' => 'Lahore',      'batch' => 2024, 'sem' => 1, 'adm' => $R,  'prev_q' => 'FSc', 'prev_m' => 910.00, 'prev_b' => 'BISE Lahore',     'prev_y' => 2024, 'status' => $A, 'blood' => 'A-', 'dept' => $deptCS,  'prog' => $progCS,  'year' => $year2024],
            ['roll' => 'CS-2024-0005', 'name' => 'Waqas Ahmed Chaudhry', 'father' => 'Ahmed Chaudhry',      'gender' => $M, 'cnic' => '35303-5565005-3', 'phone' => '0345-5000005', 'email' => 'waqas.ahmed@student.edu.pk', 'city' => 'Gujranwala',  'batch' => 2024, 'sem' => 1, 'adm' => $SF, 'prev_q' => 'FSc', 'prev_m' => 775.00, 'prev_b' => 'BISE Gujranwala', 'prev_y' => 2024, 'status' => $A, 'blood' => 'B-', 'dept' => $deptCS,  'prog' => $progCS,  'year' => $year2024],
            ['roll' => 'CS-2024-0006', 'name' => 'Hina Baig',            'father' => 'Baig Muhammad',       'gender' => $F, 'cnic' => '35201-6676006-0', 'phone' => '0301-6000006', 'email' => 'hina.baig@student.edu.pk',   'city' => 'Lahore',      'batch' => 2024, 'sem' => 1, 'adm' => $S,  'prev_q' => 'FSc', 'prev_m' => 965.00, 'prev_b' => 'BISE Lahore',     'prev_y' => 2024, 'status' => $A, 'blood' => 'AB+','dept' => $deptCS,  'prog' => $progCS,  'year' => $year2024],
            ['roll' => 'CS-2024-0007', 'name' => 'Talha Mehmood',        'father' => 'Abdul Mehmood',       'gender' => $M, 'cnic' => '35202-7787007-7', 'phone' => '0322-7000007', 'email' => 'talha.m@student.edu.pk',     'city' => 'Sialkot',     'batch' => 2024, 'sem' => 1, 'adm' => $R,  'prev_q' => 'ICS', 'prev_m' => 820.00, 'prev_b' => 'BISE Gujranwala', 'prev_y' => 2024, 'status' => $A, 'blood' => null, 'dept' => $deptCS,  'prog' => $progCS,  'year' => $year2024],
            ['roll' => 'CS-2024-0008', 'name' => 'Rabia Noor',           'father' => 'Noor Ahmad',          'gender' => $F, 'cnic' => '35201-8898008-4', 'phone' => '0314-8000008', 'email' => 'rabia.noor@student.edu.pk',  'city' => 'Lahore',      'batch' => 2024, 'sem' => 1, 'adm' => $R,  'prev_q' => 'FSc', 'prev_m' => 870.00, 'prev_b' => 'BISE Lahore',     'prev_y' => 2024, 'status' => $A, 'blood' => 'O-', 'dept' => $deptCS,  'prog' => $progCS,  'year' => $year2024],

            // ─── BS CS — Batch 2023 (Semester 3) ───────────────────────────────────────
            ['roll' => 'CS-2023-0001', 'name' => 'Hamza Raza',           'father' => 'Ghulam Raza',         'gender' => $M, 'cnic' => '35303-3334003-3', 'phone' => '0345-3000001', 'email' => 'hamza.raza@student.edu.pk',  'city' => 'Lahore',      'batch' => 2023, 'sem' => 3, 'adm' => $R,  'prev_q' => 'ICS', 'prev_m' => 823.00, 'prev_b' => 'BISE Gujranwala', 'prev_y' => 2023, 'status' => $A, 'blood' => null, 'dept' => $deptCS,  'prog' => $progCS,  'year' => $year2023],
            ['roll' => 'CS-2023-0002', 'name' => 'Ayesha Siddiqi',       'father' => 'Abdul Waheed Siddiqi','gender' => $F, 'cnic' => '35202-4444004-8', 'phone' => '0301-4000002', 'email' => 'ayesha.s@student.edu.pk',    'city' => 'Lahore',      'batch' => 2023, 'sem' => 3, 'adm' => $R,  'prev_q' => 'FSc', 'prev_m' => 902.00, 'prev_b' => 'BISE Lahore',     'prev_y' => 2023, 'status' => $A, 'blood' => 'O+', 'dept' => $deptCS,  'prog' => $progCS,  'year' => $year2023],
            ['roll' => 'CS-2023-0003', 'name' => 'Imran Akbar',          'father' => 'Akbar Ali',           'gender' => $M, 'cnic' => null,              'phone' => '0332-9000003', 'email' => null,                          'city' => 'Gujranwala',  'batch' => 2023, 'sem' => 2, 'adm' => $R,  'prev_q' => null,  'prev_m' => null,   'prev_b' => null,              'prev_y' => null, 'status' => StudentStatusEnum::OnLeave->value, 'blood' => null, 'dept' => $deptCS, 'prog' => $progCS, 'year' => $year2023, 'is_active' => false, 'remarks' => 'Medical leave approved for Spring 2025.'],
            ['roll' => 'CS-2023-0004', 'name' => 'Saad Malik',           'father' => 'Pervez Malik',        'gender' => $M, 'cnic' => '35201-5545004-1', 'phone' => '0311-5000004', 'email' => 'saad.malik@student.edu.pk',  'city' => 'Lahore',      'batch' => 2023, 'sem' => 3, 'adm' => $R,  'prev_q' => 'FSc', 'prev_m' => 845.00, 'prev_b' => 'BISE Lahore',     'prev_y' => 2023, 'status' => $A, 'blood' => 'B+', 'dept' => $deptCS,  'prog' => $progCS,  'year' => $year2023],
            ['roll' => 'CS-2023-0005', 'name' => 'Nadia Hussain',        'father' => 'Arif Hussain',        'gender' => $F, 'cnic' => '35202-6656005-6', 'phone' => '0322-6000005', 'email' => 'nadia.h@student.edu.pk',     'city' => 'Lahore',      'batch' => 2023, 'sem' => 3, 'adm' => $S,  'prev_q' => 'FSc', 'prev_m' => 935.00, 'prev_b' => 'BISE Lahore',     'prev_y' => 2023, 'status' => $A, 'blood' => 'A+', 'dept' => $deptCS,  'prog' => $progCS,  'year' => $year2023],
            ['roll' => 'CS-2023-0006', 'name' => 'Rehan Sarwar',         'father' => 'Sarwar Khan',         'gender' => $M, 'cnic' => '35303-7767006-9', 'phone' => '0333-7000006', 'email' => 'rehan.s@student.edu.pk',     'city' => 'Faisalabad',  'batch' => 2023, 'sem' => 3, 'adm' => $R,  'prev_q' => 'ICS', 'prev_m' => 795.00, 'prev_b' => 'BISE Faisalabad', 'prev_y' => 2023, 'status' => $A, 'blood' => null, 'dept' => $deptCS,  'prog' => $progCS,  'year' => $year2023],

            // ─── BS CS — Batch 2022 (Semester 5) ───────────────────────────────────────
            ['roll' => 'CS-2022-0001', 'name' => 'Usman Tariq',          'father' => 'Tariq Mehmood',       'gender' => $M, 'cnic' => '35201-5555005-5', 'phone' => '0311-5000001', 'email' => 'usman.tariq@student.edu.pk', 'city' => 'Lahore',      'batch' => 2022, 'sem' => 5, 'adm' => $R,  'prev_q' => 'FSc', 'prev_m' => 867.00, 'prev_b' => 'BISE Lahore',     'prev_y' => 2022, 'status' => $A, 'blood' => null, 'dept' => $deptCS,  'prog' => $progCS,  'year' => $year2022],
            ['roll' => 'CS-2022-0002', 'name' => 'Zara Noor',            'father' => 'Noor Muhammad',       'gender' => $F, 'cnic' => '35202-6666006-2', 'phone' => '0322-6000002', 'email' => 'zara.noor@student.edu.pk',   'city' => 'Lahore',      'batch' => 2022, 'sem' => 5, 'adm' => $SF, 'prev_q' => 'FSc', 'prev_m' => 744.00, 'prev_b' => 'BISE Lahore',     'prev_y' => 2022, 'status' => $A, 'blood' => null, 'dept' => $deptCS,  'prog' => $progCS,  'year' => $year2022],
            ['roll' => 'CS-2022-0003', 'name' => 'Sarah Jamil',          'father' => 'Jamil Ahmad',         'gender' => $F, 'cnic' => null,              'phone' => '0301-1000003', 'email' => 'sarah.jamil@alumni.edu.pk',  'city' => 'Lahore',      'batch' => 2022, 'sem' => 8, 'adm' => $R,  'prev_q' => null,  'prev_m' => null,   'prev_b' => null,              'prev_y' => null, 'status' => StudentStatusEnum::Graduated->value, 'blood' => null, 'dept' => $deptCS, 'prog' => $progCS, 'year' => $year2022, 'is_active' => false, 'remarks' => 'Graduated December 2025.'],
            ['roll' => 'CS-2022-0004', 'name' => 'Shoaib Mannan',        'father' => 'Abdul Mannan',        'gender' => $M, 'cnic' => '35202-8878004-5', 'phone' => '0314-8000004', 'email' => 'shoaib.m@student.edu.pk',    'city' => 'Lahore',      'batch' => 2022, 'sem' => 5, 'adm' => $R,  'prev_q' => 'FSc', 'prev_m' => 878.00, 'prev_b' => 'BISE Lahore',     'prev_y' => 2022, 'status' => $A, 'blood' => 'O+', 'dept' => $deptCS,  'prog' => $progCS,  'year' => $year2022],
            ['roll' => 'CS-2022-0005', 'name' => 'Madiha Tariq',         'father' => 'Tariq Nawaz',         'gender' => $F, 'cnic' => '35201-9989005-2', 'phone' => '0305-9000005', 'email' => 'madiha.t@student.edu.pk',    'city' => 'Sialkot',     'batch' => 2022, 'sem' => 5, 'adm' => $S,  'prev_q' => 'FSc', 'prev_m' => 948.00, 'prev_b' => 'BISE Gujranwala', 'prev_y' => 2022, 'status' => $A, 'blood' => 'B+', 'dept' => $deptCS,  'prog' => $progCS,  'year' => $year2022],
            ['roll' => 'CS-2022-0006', 'name' => 'Abdullah Baig',        'father' => 'Baig Ahmad',          'gender' => $M, 'cnic' => '35303-0090006-9', 'phone' => '0300-0000006', 'email' => 'abdullah.b@student.edu.pk',  'city' => 'Gujranwala',  'batch' => 2022, 'sem' => 5, 'adm' => $R,  'prev_q' => 'ICS', 'prev_m' => 801.00, 'prev_b' => 'BISE Gujranwala', 'prev_y' => 2022, 'status' => $A, 'blood' => null, 'dept' => $deptCS,  'prog' => $progCS,  'year' => $year2022],

            // ─── B.Ed — Batch 2024 (Semester 1) ────────────────────────────────────────
            ['roll' => 'EDU-2024-0001', 'name' => 'Sana Rehman',         'father' => 'Abdul Rehman',        'gender' => $F, 'cnic' => '35201-7777007-6', 'phone' => '0314-7000001', 'email' => 'sana.rehman@student.edu.pk', 'city' => 'Lahore',      'batch' => 2024, 'sem' => 1, 'adm' => $R,  'prev_q' => 'BA',  'prev_m' => 445.00, 'prev_b' => 'University of Punjab', 'prev_y' => 2023, 'status' => $A, 'blood' => null, 'dept' => $deptEdu, 'prog' => $progBEd, 'year' => $year2024],
            ['roll' => 'EDU-2024-0002', 'name' => 'Bilal Hussain',       'father' => 'Sajjad Hussain',      'gender' => $M, 'cnic' => '35303-8888008-1', 'phone' => '0305-8000002', 'email' => 'bilal.h@student.edu.pk',     'city' => 'Sheikhupura','batch' => 2024, 'sem' => 1, 'adm' => $R,  'prev_q' => 'BA',  'prev_m' => null,   'prev_b' => null,              'prev_y' => null, 'status' => $A, 'blood' => null, 'dept' => $deptEdu, 'prog' => $progBEd, 'year' => $year2024],
            ['roll' => 'EDU-2024-0003', 'name' => 'Asma Riaz',           'father' => 'Riaz Ahmad',          'gender' => $F, 'cnic' => '35202-9099003-3', 'phone' => '0321-9000003', 'email' => 'asma.riaz@student.edu.pk',   'city' => 'Lahore',      'batch' => 2024, 'sem' => 1, 'adm' => $R,  'prev_q' => 'BSc', 'prev_m' => 512.00, 'prev_b' => 'Punjab University',    'prev_y' => 2023, 'status' => $A, 'blood' => 'A+', 'dept' => $deptEdu, 'prog' => $progBEd, 'year' => $year2024],
            ['roll' => 'EDU-2024-0004', 'name' => 'Imran Butt',          'father' => 'Butt Nawaz',          'gender' => $M, 'cnic' => '35201-1109004-7', 'phone' => '0333-1000004', 'email' => 'imran.butt@student.edu.pk',  'city' => 'Lahore',      'batch' => 2024, 'sem' => 1, 'adm' => $SF, 'prev_q' => 'BA',  'prev_m' => 420.00, 'prev_b' => 'Allama Iqbal Open',    'prev_y' => 2022, 'status' => $A, 'blood' => null, 'dept' => $deptEdu, 'prog' => $progBEd, 'year' => $year2024],
            ['roll' => 'EDU-2024-0005', 'name' => 'Uzma Akbar',          'father' => 'Akbar Hussain',       'gender' => $F, 'cnic' => '35202-2210005-4', 'phone' => '0345-2000005', 'email' => 'uzma.akbar@student.edu.pk',  'city' => 'Kasur',       'batch' => 2024, 'sem' => 1, 'adm' => $R,  'prev_q' => 'BA',  'prev_m' => 490.00, 'prev_b' => 'Punjab University',    'prev_y' => 2023, 'status' => $A, 'blood' => 'O+', 'dept' => $deptEdu, 'prog' => $progBEd, 'year' => $year2024],
            ['roll' => 'EDU-2024-0006', 'name' => 'Faisal Javed',        'father' => 'Javed Iqbal',         'gender' => $M, 'cnic' => '35303-3321006-1', 'phone' => '0312-3000006', 'email' => 'faisal.j@student.edu.pk',    'city' => 'Sheikhupura','batch' => 2024, 'sem' => 1, 'adm' => $R,  'prev_q' => 'BSc', 'prev_m' => 530.00, 'prev_b' => 'Lahore College',        'prev_y' => 2023, 'status' => $A, 'blood' => null, 'dept' => $deptEdu, 'prog' => $progBEd, 'year' => $year2024],

            // ─── B.Ed — Batch 2023 (Semester 3) ────────────────────────────────────────
            ['roll' => 'EDU-2023-0001', 'name' => 'Naila Jamil',         'father' => 'Jamil Qureshi',       'gender' => $F, 'cnic' => '35201-4432001-9', 'phone' => '0311-4000007', 'email' => 'naila.j@student.edu.pk',     'city' => 'Lahore',      'batch' => 2023, 'sem' => 3, 'adm' => $R,  'prev_q' => 'MA',  'prev_m' => 560.00, 'prev_b' => 'Punjab University',    'prev_y' => 2022, 'status' => $A, 'blood' => 'B+', 'dept' => $deptEdu, 'prog' => $progBEd, 'year' => $year2023],
            ['roll' => 'EDU-2023-0002', 'name' => 'Danish Nawaz',        'father' => 'Nawaz Ahmad',         'gender' => $M, 'cnic' => '35202-5543002-6', 'phone' => '0322-5000008', 'email' => 'danish.n@student.edu.pk',    'city' => 'Lahore',      'batch' => 2023, 'sem' => 3, 'adm' => $SF, 'prev_q' => 'BA',  'prev_m' => 430.00, 'prev_b' => 'Punjab University',    'prev_y' => 2022, 'status' => $A, 'blood' => null, 'dept' => $deptEdu, 'prog' => $progBEd, 'year' => $year2023],
            ['roll' => 'EDU-2023-0003', 'name' => 'Saima Cheema',        'father' => 'Cheema Ghulam',       'gender' => $F, 'cnic' => '35303-6654003-3', 'phone' => '0333-6000009', 'email' => 'saima.c@student.edu.pk',     'city' => 'Faisalabad',  'batch' => 2023, 'sem' => 3, 'adm' => $R,  'prev_q' => 'BSc', 'prev_m' => 575.00, 'prev_b' => 'Faisalabad University','prev_y' => 2022, 'status' => $A, 'blood' => 'A-', 'dept' => $deptEdu, 'prog' => $progBEd, 'year' => $year2023],
            ['roll' => 'EDU-2023-0004', 'name' => 'Rizwan Aslam',        'father' => 'Aslam Pervaiz',       'gender' => $M, 'cnic' => '35201-7765004-0', 'phone' => '0314-7000010', 'email' => 'rizwan.a@student.edu.pk',    'city' => 'Nankana Sahib','batch'=> 2023, 'sem' => 3, 'adm' => $R,  'prev_q' => 'BA',  'prev_m' => 465.00, 'prev_b' => 'Punjab University',    'prev_y' => 2022, 'status' => $A, 'blood' => null, 'dept' => $deptEdu, 'prog' => $progBEd, 'year' => $year2023],
        ];

        foreach ($students as $d) {
            Student::firstOrCreate(
                ['roll_number' => $d['roll']],
                [
                    'department_id'          => $d['dept']?->id,
                    'academic_program_id'    => $d['prog']?->id,
                    'academic_year_id'       => $d['year']?->id,
                    'roll_number'            => $d['roll'],
                    'name'                   => $d['name'],
                    'father_name'            => $d['father'],
                    'gender'                 => $d['gender'],
                    'cnic'                   => $d['cnic'] ?? null,
                    'phone'                  => $d['phone'],
                    'email'                  => $d['email'] ?? null,
                    'city'                   => $d['city'],
                    'province'               => $PUN,
                    'batch_year'             => $d['batch'],
                    'current_semester'       => $d['sem'],
                    'admission_date'         => $d['batch'] . '-09-01',
                    'admission_type'         => $d['adm'],
                    'previous_qualification' => $d['prev_q'] ?? null,
                    'previous_marks'         => $d['prev_m'] ?? null,
                    'previous_board'         => $d['prev_b'] ?? null,
                    'previous_year'          => $d['prev_y'] ?? null,
                    'status'                 => $d['status'],
                    'is_active'              => $d['is_active'] ?? true,
                    'blood_group'            => $d['blood'] ?? null,
                    'nationality'            => 'Pakistani',
                    'remarks'                => $d['remarks'] ?? null,
                ]
            );
        }
    }
}
