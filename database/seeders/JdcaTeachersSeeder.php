<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

/**
 * JDCA faculty list, mapped to departments by subject where one exists.
 * Single source of truth for both fresh seeding and the live-data migration.
 */
class JdcaTeachersSeeder extends Seeder
{
    /**
     * @return array<int,array<string,mixed>>
     */
    public static function teachers(): array
    {
        // emp, name, gender, email, phone, qualification, subject, year, experience, department-slug|null
        return [
            ['JDCA-T-001', 'Ibrar Hussain', 'male', 'Ibrar.astori@gmil.com', '03215022568', 'M.Phil', 'Education', 2024, 9, 'department-of-education'],
            ['JDCA-T-002', 'Aurangzeb Ali', 'male', 'Aliorangzaib64@gmail.com', '03555789646', 'M.Phil', 'Education', 2025, 5, 'department-of-education'],
            ['JDCA-T-003', 'Ikram Hussain', 'male', 'Ikramalisakhi121@gmail.com', '03555009916', 'M.Phil', 'Education', 2025, 4, 'department-of-education'],
            ['JDCA-T-004', 'Mujeeb Ur Rehman', 'male', 'Mujeebmirza404@gmail.com', '03555934923', 'M.Phil', 'Physics', 2024, 5, null],
            ['JDCA-T-005', 'Tariq Hussain', 'male', 'Tariqmalik7356@gmail.com', '03555410511', 'BS', 'English', 2020, 3, 'department-of-english'],
            ['JDCA-T-006', 'Fatima Niamat Khan', 'female', 'Fatimaniamatullah6@gmail.com', '03109385353', 'BS', 'Biology', 2022, 3, null],
            ['JDCA-T-007', 'Asima Juma', 'female', 'asimaqau@gmai.com', '03129801799', 'BS (M.Phil continuing)', 'Chemistry, Biology', 2012, 10, null],
            ['JDCA-T-008', 'Mehreen', 'female', 'Mehreenbatool21@gmail.com', '03119719515', 'BS', 'Urdu', 2021, 3, null],
            ['JDCA-T-009', 'Kaneez Fatima', 'female', 'Kaneezz745@gmail.com', '03554358079', 'BS', 'Urdu', 2023, 3, null],
            ['JDCA-T-010', 'Sumia Aziz', 'female', 'Sumiaaziz112@gmail.com', '03554272243', 'BS', 'Zoology', 2024, 4, null],
            ['JDCA-T-011', 'Habib Ur Rehman', 'male', 'Habibhasrat1122@gmail.com', '03491908789', 'BS', 'Education', 2024, 3, 'department-of-education'],
            ['JDCA-T-012', 'Tania Irum', 'female', 'farmantanusalar@gmail.com', '03317443458', 'BS', 'Health & Physical', 2023, 2, 'department-of-physical-education'],
            ['JDCA-T-013', 'Zakia Batool', 'female', 'Zakiabatool70@gmail.com', '03155999160', 'BS', 'English', 2018, 6, 'department-of-english'],
            ['JDCA-T-014', 'Syeda Kishwar Batool', 'female', 'Syyedakishwar76@gmail.com', '03125684175', 'BS', 'Mathematics', 2023, 2, null],
            ['JDCA-T-015', 'Shahid Hussian', 'male', 'Shahid343414@gmail.com', '03115518080', 'BS', 'Computer Science', 2020, 2, 'department-of-computer-science'],
            ['JDCA-T-016', 'Arif Ali', 'male', 'Arifaliraj@gmail.com', '03129776585', 'BS', 'Computer Science', 2016, 2, 'department-of-computer-science'],
            // Same person/email as JDCA-T-011 (different subject) — email left null to respect the unique constraint.
            ['JDCA-T-017', 'Habib Ur Rehman', 'male', null, '03491908789', 'BS', 'Social Study', 2023, 2, 'department-of-sociology'],
            ['JDCA-T-018', 'Muhammad Usama', 'male', 'Musama6040@gmail.com', '03554459888', 'BS', 'Chemistry', 2022, 4, null],
            ['JDCA-T-019', 'Taruf Hussain', 'male', 'Tarufhussain111@gmail.com', '03555792868', 'BS', 'Mathematics', 2019, 4, null],
        ];
    }

    public static function upsertAll(): void
    {
        foreach (self::teachers() as [$emp, $name, $gender, $email, $phone, $qual, $subject, $year, $exp, $deptSlug]) {
            $departmentId = $deptSlug ? Department::where('slug', $deptSlug)->value('id') : null;

            Teacher::updateOrCreate(
                ['employee_id' => $emp],
                [
                    'department_id'         => $departmentId,
                    'name'                  => $name,
                    'gender'                => $gender,
                    'email'                 => $email,
                    'phone'                 => $phone,
                    'highest_qualification' => $qual,
                    'specialization'        => $subject,
                    'qualification_year'    => $year,
                    'experience_years'      => $exp,
                    'designation'           => 'Lecturer',
                    'employment_type'       => 'permanent',
                    'status'                => 'active',
                    'is_active'             => true,
                ]
            );
        }
    }

    public function run(): void
    {
        self::upsertAll();
    }
}
