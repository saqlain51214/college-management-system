<?php

namespace Database\Seeders;

use App\Enums\DepartmentTypeEnum;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            [
                'name'            => 'Department of Education',
                'name_urdu'       => 'شعبہ تعلیم',
                'code'            => 'DEPT-EDU',
                'type'            => DepartmentTypeEnum::Academic->value,
                'hod_name'        => 'Prof. Dr. Muhammad Arif',
                'hod_designation' => 'Associate Professor',
                'hod_message'     => '<p>Welcome to the Department of Education. Our mission is to produce quality teachers and educators who will transform the future of Pakistan.</p>',
                'description'     => 'The Department of Education offers BS Education, B.Ed and M.Ed programs aligned with HEC guidelines.',
                'vision'          => '<p>To become a centre of excellence in teacher education in Pakistan.</p>',
                'mission'         => '<p>To produce skilled, reflective and ethical educators for the nation.</p>',
                'email'           => 'education@college.edu.pk',
                'phone'           => '03001234567',
                'sort_order'      => 1,
                'is_active'       => true,
                'show_on_website' => true,
            ],
            [
                'name'            => 'Department of Physical Education',
                'name_urdu'       => 'شعبہ جسمانی تعلیم',
                'code'            => 'DEPT-PHY',
                'type'            => DepartmentTypeEnum::Academic->value,
                'hod_name'        => 'Mr. Usman Khalid',
                'hod_designation' => 'Assistant Professor',
                'hod_message'     => '<p>Physical education is not just sports — it is the foundation of a healthy mind and body. We welcome you to our department.</p>',
                'description'     => 'The Department of Physical Education focuses on sports science, physical fitness and health education.',
                'vision'          => '<p>Developing physically fit and mentally strong graduates.</p>',
                'mission'         => '<p>To promote healthy lifestyle and sports culture in Pakistan.</p>',
                'email'           => 'physed@college.edu.pk',
                'phone'           => '03011234567',
                'sort_order'      => 2,
                'is_active'       => true,
                'show_on_website' => true,
            ],
            [
                'name'            => 'Department of Computer Science',
                'name_urdu'       => 'شعبہ کمپیوٹر سائنس',
                'code'            => 'DEPT-CS',
                'type'            => DepartmentTypeEnum::Academic->value,
                'hod_name'        => 'Mr. Tariq Mehmood',
                'hod_designation' => 'Lecturer',
                'hod_message'     => '<p>In the age of technology, computer science is the engine of progress. Join us to build the digital future of Pakistan.</p>',
                'description'     => 'The Department of Computer Science offers BS Computer Science with modern curriculum covering programming, databases, networks and AI.',
                'vision'          => '<p>To be the leading CS department producing world-class software professionals.</p>',
                'mission'         => '<p>Equipping students with practical and theoretical knowledge in computing.</p>',
                'email'           => 'cs@college.edu.pk',
                'phone'           => '03021234567',
                'sort_order'      => 3,
                'is_active'       => true,
                'show_on_website' => true,
            ],
            [
                'name'            => 'Department of English',
                'name_urdu'       => 'شعبہ انگریزی',
                'code'            => 'DEPT-ENG',
                'type'            => DepartmentTypeEnum::Academic->value,
                'hod_name'        => 'Ms. Sana Rehman',
                'hod_designation' => 'Assistant Professor',
                'hod_message'     => '<p>Language is the key to the world. Our department prepares students to communicate confidently and write professionally in English.</p>',
                'description'     => 'The Department of English provides BS English with focus on linguistics, literature and communication skills.',
                'vision'          => '<p>Producing confident communicators and critical thinkers.</p>',
                'mission'         => '<p>To enhance English language competency through modern teaching methods.</p>',
                'email'           => 'english@college.edu.pk',
                'phone'           => '03031234567',
                'sort_order'      => 4,
                'is_active'       => true,
                'show_on_website' => true,
            ],
            [
                'name'            => 'Department of Sociology',
                'name_urdu'       => 'شعبہ عمرانیات',
                'code'            => 'DEPT-SOC',
                'type'            => DepartmentTypeEnum::Academic->value,
                'hod_name'        => 'Dr. Ayesha Siddiqui',
                'hod_designation' => 'Associate Professor',
                'hod_message'     => '<p>Understanding society is the first step to improving it. Our department prepares students to become agents of positive change.</p>',
                'description'     => 'The Department of Sociology offers BS Sociology covering social theory, research methods and community development.',
                'vision'          => '<p>Creating socially conscious graduates who serve the community.</p>',
                'mission'         => '<p>Fostering critical understanding of social structures and issues in Pakistan.</p>',
                'email'           => 'sociology@college.edu.pk',
                'phone'           => '03041234567',
                'sort_order'      => 5,
                'is_active'       => true,
                'show_on_website' => true,
            ],
            [
                'name'            => 'Department of Continuous Education',
                'name_urdu'       => 'شعبہ مسلسل تعلیم',
                'code'            => 'DEPT-CE',
                'type'            => DepartmentTypeEnum::Academic->value,
                'hod_name'        => 'Mr. Bilal Ahmed',
                'hod_designation' => 'Lecturer',
                'hod_message'     => '<p>Learning never stops. Our department provides lifelong learning opportunities for professionals and working individuals.</p>',
                'description'     => 'The Department of Continuous Education offers short courses, diplomas and certificate programs for working professionals.',
                'vision'          => '<p>Making quality education accessible to everyone at every stage of life.</p>',
                'mission'         => '<p>Providing flexible learning pathways for professional development.</p>',
                'email'           => 'ce@college.edu.pk',
                'phone'           => '03051234567',
                'sort_order'      => 6,
                'is_active'       => true,
                'show_on_website' => true,
            ],
            [
                'name'            => 'Administration Department',
                'name_urdu'       => 'انتظامی شعبہ',
                'code'            => 'DEPT-ADMIN',
                'type'            => DepartmentTypeEnum::Administrative->value,
                'hod_name'        => 'Mr. Faisal Nawaz',
                'hod_designation' => 'Registrar',
                'hod_message'     => '<p>The administration department ensures smooth functioning of all college operations.</p>',
                'description'     => 'Handles admissions, examinations, student records and all administrative affairs of the college.',
                'vision'          => null,
                'mission'         => null,
                'email'           => 'admin@college.edu.pk',
                'phone'           => '03061234567',
                'sort_order'      => 7,
                'is_active'       => true,
                'show_on_website' => false,
            ],
        ];

        foreach ($departments as $data) {
            $data['slug'] = Str::slug($data['name']);
            Department::firstOrCreate(['slug' => $data['slug']], $data);
        }

        $this->command->info('✅ ' . count($departments) . ' departments seeded successfully.');
    }
}

