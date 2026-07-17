<?php

namespace Database\Seeders;

use App\Enums\DegreeTypeEnum;
use App\Models\AcademicProgram;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Seeds all JDCA departments (upsert) and the exact programme list
 * requested for the online admission form, including the new ADP programmes.
 */
class JdcaProgramsSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Upsert departments ──────────────────────────────────────────────
        $this->call(DepartmentSeeder::class);

        // ── 2. Helper ─────────────────────────────────────────────────────────
        $dept = fn(string $slug) => Department::where('slug', $slug)->value('id');

        // ── 3. All JDCA programmes (firstOrCreate by slug) ────────────────────
        $programs = [

            // ── Department of Education ──────────────────────────────────────
            [
                'department_id'      => $dept('department-of-education'),
                'name'               => 'Associate Degree in Education',
                'short_name'         => 'ADE',
                'slug'               => 'associate-degree-in-education',
                'code'               => 'ADE',
                'degree_type'        => DegreeTypeEnum::ADP->value,
                'duration_years'     => 2,
                'total_semesters'    => 4,
                'total_credit_hours' => 66,
                'description'        => 'A two-year associate degree programme preparing students for primary and elementary school teaching. Recognised by HEC and provincial education departments.',
                'eligibility'        => 'HSSC (F.A / F.Sc) with minimum 45% marks from a recognised board.',
                'scope'              => 'Graduates are eligible for primary and elementary teaching posts in government and private schools and can upgrade to B.Ed.',
                'is_active'          => true,
                'show_on_website'    => true,
                'sort_order'         => 1,
            ],
            [
                'department_id'      => $dept('department-of-education'),
                'name'               => 'B.Ed 1.5 Year',
                'short_name'         => 'B.Ed (1.5 yr)',
                'slug'               => 'bed-1-5-year',
                'code'               => 'BED-1.5',
                'degree_type'        => DegreeTypeEnum::BEd->value,
                'duration_years'     => 2,        // stored as integer, label clarifies
                'total_semesters'    => 3,
                'total_credit_hours' => 54,
                'description'        => 'An 18-month Bachelor of Education programme designed for in-service teachers who hold a 14-year degree. Provides a fast pathway to professional teaching qualification.',
                'eligibility'        => 'BA / BSc (14 years of education) with minimum 45% marks and currently serving as a teacher.',
                'scope'              => 'Enhances professional standing for in-service teachers; satisfies HEC minimum teaching qualification requirements.',
                'is_active'          => true,
                'show_on_website'    => true,
                'sort_order'         => 2,
            ],
            [
                'department_id'      => $dept('department-of-education'),
                'name'               => 'B.Ed 2.5 Year',
                'short_name'         => 'B.Ed (2.5 yr)',
                'slug'               => 'bed-2-5-year',
                'code'               => 'BED-2.5',
                'degree_type'        => DegreeTypeEnum::BEd->value,
                'duration_years'     => 3,
                'total_semesters'    => 5,
                'total_credit_hours' => 84,
                'description'        => 'A 2.5-year Bachelor of Education programme for holders of a 14-year degree who wish to qualify as secondary school teachers with full professional credentials.',
                'eligibility'        => 'BA / BSc (14 years of education) with minimum 45% marks.',
                'scope'              => 'Qualifies graduates for secondary and higher secondary school teaching positions. Can pursue M.Ed for further advancement.',
                'is_active'          => true,
                'show_on_website'    => true,
                'sort_order'         => 3,
            ],
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
                'description'        => 'A four-year undergraduate programme preparing students for professional teaching careers at secondary and higher secondary levels.',
                'eligibility'        => 'F.A / F.Sc with minimum 45% marks from a recognised board.',
                'scope'              => 'Government and private school teaching, education administration, curriculum development, and pathway to M.Ed.',
                'is_active'          => true,
                'show_on_website'    => true,
                'sort_order'         => 4,
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
                'description'        => 'A two-year postgraduate programme developing advanced competencies in educational leadership, curriculum design, research and pedagogy.',
                'eligibility'        => 'B.Ed or equivalent 16-year education with minimum 45% marks from an HEC recognised university.',
                'scope'              => 'Educational administrators, principals, college lecturers, education researchers and curriculum specialists.',
                'is_active'          => true,
                'show_on_website'    => true,
                'sort_order'         => 5,
            ],

            // ── Department of Physical Education ─────────────────────────────
            [
                'department_id'      => $dept('department-of-physical-education'),
                'name'               => 'Associate Degree in Health & Physical Education',
                'short_name'         => 'AD HPE',
                'slug'               => 'associate-degree-health-physical-education',
                'code'               => 'ADHPE',
                'degree_type'        => DegreeTypeEnum::ADP->value,
                'duration_years'     => 2,
                'total_semesters'    => 4,
                'total_credit_hours' => 66,
                'description'        => 'A two-year associate degree combining health sciences and physical education. Prepares students for teaching physical education at school level.',
                'eligibility'        => 'HSSC (F.A / F.Sc) with minimum 45% marks. Applicants must be physically fit.',
                'scope'              => 'Physical education teaching in schools, sports coaching, fitness instruction and can upgrade to BS Physical Education.',
                'is_active'          => true,
                'show_on_website'    => true,
                'sort_order'         => 1,
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
                'description'        => 'A four-year programme combining theoretical knowledge and practical training in sports science, human anatomy, coaching, and physical fitness education.',
                'eligibility'        => 'F.A / F.Sc with minimum 45% marks. Applicants must be physically fit and may be required to pass a physical fitness test.',
                'scope'              => 'Physical education teachers, sports coaches, gym instructors, fitness trainers and sports management organisations.',
                'is_active'          => true,
                'show_on_website'    => true,
                'sort_order'         => 2,
            ],

            // ── Department of Sociology ───────────────────────────────────────
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
                'description'        => 'A two-year postgraduate programme exploring social structures, cultural patterns, social change, research methods and contemporary sociological theories.',
                'eligibility'        => 'BA / BSc with Sociology as a major subject and minimum 45% marks from an HEC recognised university.',
                'scope'              => 'Social workers, NGO officers, researchers, lecturers, community development officers and government social sector employees.',
                'is_active'          => true,
                'show_on_website'    => true,
                'sort_order'         => 1,
            ],

            // ── Department of Computer Science ───────────────────────────────
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
                'description'        => 'A four-year HEC-recognised programme covering programming, algorithms, databases, software engineering and artificial intelligence.',
                'eligibility'        => 'F.Sc (Pre-Engineering / Computer Science) or equivalent with minimum 50% marks.',
                'scope'              => 'Software engineers, web developers, data analysts, system analysts, IT managers and MS CS pathway.',
                'is_active'          => true,
                'show_on_website'    => true,
                'sort_order'         => 1,
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
                'description'        => 'A one-year certificate programme covering essential computer skills: MS Office, internet, basic programming and digital literacy.',
                'eligibility'        => 'Matriculation (SSC) from any recognised board with minimum 33% marks.',
                'scope'              => 'Data entry operators, office assistants, computer lab assistants and pathway to BS CS.',
                'is_active'          => true,
                'show_on_website'    => true,
                'sort_order'         => 2,
            ],

            // ── Department of English ────────────────────────────────────────
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
                'description'        => 'A four-year programme focusing on English literature, linguistics, communication skills and research methodology.',
                'eligibility'        => 'F.A / F.Sc with English as a compulsory subject and minimum 45% marks.',
                'scope'              => 'English teachers, content writers, translators, journalists, communication officers and MA English pathway.',
                'is_active'          => true,
                'show_on_website'    => true,
                'sort_order'         => 1,
            ],

            // ── Department of Continuous Education ───────────────────────────
            [
                'department_id'      => $dept('department-of-continuous-education'),
                'name'               => 'Diploma in Education',
                'short_name'         => 'D.Ed',
                'slug'               => 'diploma-in-education',
                'code'               => 'DED',
                'degree_type'        => DegreeTypeEnum::Diploma->value,
                'duration_years'     => 1,
                'total_semesters'    => 2,
                'total_credit_hours' => 30,
                'description'        => 'A one-year diploma providing foundational teacher training for primary level education: child psychology, classroom management and basic pedagogy.',
                'eligibility'        => 'Matriculation (SSC) with minimum 45% marks from a recognised board.',
                'scope'              => 'Primary school teaching positions in government and private schools. Can upgrade to B.Ed.',
                'is_active'          => true,
                'show_on_website'    => true,
                'sort_order'         => 1,
            ],
        ];

        foreach ($programs as $data) {
            AcademicProgram::firstOrCreate(['slug' => $data['slug']], $data);
        }

        $count = count($programs);
        $this->command->info("✅ {$count} JDCA programmes seeded (firstOrCreate — no duplicates).");
    }
}
