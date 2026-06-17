<?php

namespace Database\Seeders;

use App\Enums\DegreeTypeEnum;
use App\Models\AcademicProgram;
use App\Models\Department;
use Illuminate\Database\Seeder;

class AcademicProgramSeeder extends Seeder
{
    public function run(): void
    {
        $dept = fn(string $slug) => Department::where('slug', $slug)->value('id');

        $programs = [
            [
                'department_id'      => $dept('department-of-education'),
                'name'               => 'Bachelor of Education',
                'short_name'         => 'B.Ed',
                'slug'               => 'bachelor-of-education',
                'code'               => 'BED',
                'degree_type'        => DegreeTypeEnum::BEd->value,
                'duration_years'     => 4,
                'total_semesters'    => 8,
                'total_credit_hours' => 124,
                'description'        => 'A four-year undergraduate program that prepares students for a professional career in teaching and education management at secondary and higher secondary levels.',
                'eligibility'        => 'F.A / F.Sc with minimum 45% marks from a recognized Board of Intermediate & Secondary Education.',
                'scope'              => 'Graduates can work as teachers in government and private schools, education administrators, curriculum developers, and can pursue M.Ed for further academic career.',
                'is_active'          => true,
                'show_on_website'    => true,
                'sort_order'         => 1,
            ],
            [
                'department_id'      => $dept('department-of-education'),
                'name'               => 'Master of Education',
                'short_name'         => 'M.Ed',
                'slug'               => 'master-of-education',
                'code'               => 'MED',
                'degree_type'        => DegreeTypeEnum::MEd->value,
                'duration_years'     => 2,
                'total_semesters'    => 4,
                'total_credit_hours' => 60,
                'description'        => 'A two-year postgraduate program designed to develop advanced competencies in educational leadership, curriculum design, research, and pedagogy.',
                'eligibility'        => 'B.Ed or equivalent 16-year education in a related field with minimum 45% marks from an HEC recognized university.',
                'scope'              => 'Graduates can serve as educational administrators, principals, college lecturers, education researchers, and curriculum specialists.',
                'is_active'          => true,
                'show_on_website'    => true,
                'sort_order'         => 2,
            ],
            [
                'department_id'      => $dept('department-of-computer-science'),
                'name'               => 'Bachelor of Science in Computer Science',
                'short_name'         => 'BS CS',
                'slug'               => 'bs-computer-science',
                'code'               => 'BS-CS',
                'degree_type'        => DegreeTypeEnum::BS->value,
                'duration_years'     => 4,
                'total_semesters'    => 8,
                'total_credit_hours' => 130,
                'description'        => 'A four-year HEC-recognized undergraduate program covering core computer science disciplines including programming, algorithms, databases, software engineering, and artificial intelligence.',
                'eligibility'        => 'F.Sc (Pre-Engineering / Computer Science) or equivalent with minimum 50% marks. Candidates with F.A and Mathematics are also eligible.',
                'scope'              => 'Graduates pursue careers as software engineers, web developers, data analysts, system analysts, IT managers, and can apply for MS CS for academic advancement.',
                'is_active'          => true,
                'show_on_website'    => true,
                'sort_order'         => 3,
            ],
            [
                'department_id'      => $dept('department-of-english'),
                'name'               => 'Bachelor of Science in English',
                'short_name'         => 'BS English',
                'slug'               => 'bs-english',
                'code'               => 'BS-ENG',
                'degree_type'        => DegreeTypeEnum::BS->value,
                'duration_years'     => 4,
                'total_semesters'    => 8,
                'total_credit_hours' => 124,
                'description'        => 'A four-year undergraduate program focusing on English literature, linguistics, communication skills, and research methodology to produce competent educators and language professionals.',
                'eligibility'        => 'F.A / F.Sc with English as a compulsory subject and minimum 45% marks from an HEC recognized board.',
                'scope'              => 'Graduates can work as English teachers, content writers, translators, journalists, communication officers, and can pursue MA English or M.Ed.',
                'is_active'          => true,
                'show_on_website'    => true,
                'sort_order'         => 4,
            ],
            [
                'department_id'      => $dept('department-of-sociology'),
                'name'               => 'Master of Arts in Sociology',
                'short_name'         => 'MA Sociology',
                'slug'               => 'ma-sociology',
                'code'               => 'MA-SOC',
                'degree_type'        => DegreeTypeEnum::MA->value,
                'duration_years'     => 2,
                'total_semesters'    => 4,
                'total_credit_hours' => 60,
                'description'        => 'A two-year postgraduate program exploring social structures, cultural patterns, social change, research methods, and contemporary sociological theories.',
                'eligibility'        => 'BA / BSc with Sociology as a major subject and minimum 45% marks from an HEC recognized university.',
                'scope'              => 'Graduates serve as social workers, NGO officers, researchers, lecturers, community development officers, and government social sector employees.',
                'is_active'          => true,
                'show_on_website'    => true,
                'sort_order'         => 5,
            ],
            [
                'department_id'      => $dept('department-of-physical-education'),
                'name'               => 'Bachelor of Science in Physical Education',
                'short_name'         => 'BS PE',
                'slug'               => 'bs-physical-education',
                'code'               => 'BS-PE',
                'degree_type'        => DegreeTypeEnum::BS->value,
                'duration_years'     => 4,
                'total_semesters'    => 8,
                'total_credit_hours' => 124,
                'description'        => 'A four-year program combining theoretical knowledge and practical training in sports science, human anatomy, coaching, and physical fitness education.',
                'eligibility'        => 'F.A / F.Sc with minimum 45% marks. Applicants must be physically fit and may be required to pass a physical fitness test.',
                'scope'              => 'Graduates work as physical education teachers, sports coaches, gym instructors, fitness trainers, and can join sports management organizations.',
                'is_active'          => true,
                'show_on_website'    => true,
                'sort_order'         => 6,
            ],
            [
                'department_id'      => $dept('department-of-computer-science'),
                'name'               => 'Certificate in Computer Applications',
                'short_name'         => 'CCA',
                'slug'               => 'certificate-computer-applications',
                'code'               => 'CCA',
                'degree_type'        => DegreeTypeEnum::Certificate->value,
                'duration_years'     => 1,
                'total_semesters'    => 2,
                'total_credit_hours' => 30,
                'description'        => 'A one-year certificate program covering essential computer skills including MS Office, internet usage, basic programming, and digital literacy for office and administrative work.',
                'eligibility'        => 'Matriculation (SSC) from any recognized board with minimum 33% marks.',
                'scope'              => 'Graduates can work as data entry operators, office assistants, computer lab assistants, and can further pursue BS CS or diploma programs.',
                'is_active'          => true,
                'show_on_website'    => true,
                'sort_order'         => 7,
            ],
            [
                'department_id'      => $dept('department-of-continuing-education'),
                'name'               => 'Diploma in Education',
                'short_name'         => 'D.Ed',
                'slug'               => 'diploma-in-education',
                'code'               => 'DED',
                'degree_type'        => DegreeTypeEnum::Diploma->value,
                'duration_years'     => 1,
                'total_semesters'    => 2,
                'total_credit_hours' => 30,
                'description'        => 'A one-year diploma program providing foundational teacher training for primary level education. Covers child psychology, classroom management, and basic pedagogy.',
                'eligibility'        => 'Matriculation (SSC) with minimum 45% marks from a recognized board.',
                'scope'              => 'Graduates are eligible for primary school teaching positions in government and private schools. Can further upgrade to B.Ed.',
                'is_active'          => true,
                'show_on_website'    => true,
                'sort_order'         => 8,
            ],
        ];

        foreach ($programs as $data) {
            AcademicProgram::firstOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}
