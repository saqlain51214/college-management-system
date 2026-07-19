<?php

namespace Database\Seeders;

use App\Enums\AdmissionCategoryEnum;
use App\Enums\AdmissionTypeEnum;
use App\Enums\AttendanceStatusEnum;
use App\Enums\BookStatusEnum;
use App\Enums\DegreeTypeEnum;
use App\Enums\DepartmentTypeEnum;
use App\Enums\EmploymentTypeEnum;
use App\Enums\ExamTypeEnum;
use App\Enums\FeeTypeEnum;
use App\Enums\GenderEnum;
use App\Enums\PaymentMethodEnum;
use App\Enums\PaymentStatusEnum;
use App\Enums\ScholarshipStatusEnum;
use App\Enums\ScholarshipTypeEnum;
use App\Enums\SemesterTypeEnum;
use App\Enums\StudentStatusEnum;
use App\Enums\TeacherStatusEnum;
use App\Models\AcademicProgram;
use App\Models\AcademicYear;
use App\Models\Announcement;
use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\Book;
use App\Models\BookIssue;
use App\Models\Course;
use App\Models\Department;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\FeePayment;
use App\Models\FeeStructure;
use App\Models\LmsAssignment;
use App\Models\LmsMaterial;
use App\Models\NewsArticle;
use App\Models\Scholarship;
use App\Models\ScholarshipAward;
use App\Models\Semester;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Timetable;
use App\Models\WebsiteEvent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MinimalCoreDataSeeder extends Seeder
{
    public function run(): void
    {
        $department = Department::updateOrCreate(
            ['code' => 'DEPT-CS'],
            [
                'name' => 'Department of Computer Science',
                'name_urdu' => 'شعبہ کمپیوٹر سائنس',
                'slug' => 'department-of-computer-science',
                'type' => DepartmentTypeEnum::Academic->value,
                'hod_name' => 'Prof. Muhammad Asif',
                'hod_designation' => 'Head of Department',
                'hod_message' => '<p>Our department combines intermediate foundations with practical degree-level computing education for students of Astore and Gilgit-Baltistan.</p>',
                'description' => 'A focused department for intermediate and undergraduate computing pathways.',
                'vision' => '<p>To prepare confident, ethical, and career-ready learners in computing.</p>',
                'mission' => '<p>To provide affordable and practical computer science education with strong academic mentoring.</p>',
                'email' => 'cs@jdca.edu.pk',
                'phone' => '05817-123456',
                'sort_order' => 1,
                'is_active' => true,
                'show_on_website' => true,
            ]
        );

        $academicYear = AcademicYear::updateOrCreate(
            ['name' => '2026-2027'],
            [
                'start_date' => '2026-08-01',
                'end_date' => '2027-06-30',
                'is_current' => true,
                'is_active' => true,
                'description' => 'Default minimal academic year for JDCA demo and testing.',
            ]
        );

        $fallSemester = Semester::updateOrCreate(
            ['academic_year_id' => $academicYear->id, 'name' => 'Fall 2026'],
            [
                'academic_year_id' => $academicYear->id,
                'type' => SemesterTypeEnum::Fall->value,
                'name' => 'Fall 2026',
                'number' => 1,
                'start_date' => '2026-08-10',
                'end_date' => '2026-12-31',
                'is_current' => true,
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        $springSemester = Semester::updateOrCreate(
            ['academic_year_id' => $academicYear->id, 'name' => 'Spring 2027'],
            [
                'academic_year_id' => $academicYear->id,
                'type' => SemesterTypeEnum::Spring->value,
                'name' => 'Spring 2027',
                'number' => 2,
                'start_date' => '2027-01-11',
                'end_date' => '2027-05-31',
                'is_current' => false,
                'is_active' => true,
                'sort_order' => 2,
            ]
        );

        $intermediateProgram = AcademicProgram::updateOrCreate(
            ['code' => 'ICS'],
            [
                'department_id' => $department->id,
                'name' => 'Intermediate in Computer Science',
                'short_name' => 'ICS',
                'slug' => 'intermediate-in-computer-science',
                'degree_type' => DegreeTypeEnum::Intermediate->value,
                'admission_category' => AdmissionCategoryEnum::Intermediate->value,
                'duration_years' => 2,
                'total_semesters' => 4,
                'total_credit_hours' => 0,
                'description' => 'Two-year intermediate programme for students planning to continue into BS Computer Science and related fields.',
                'eligibility' => 'Matric with Science or Computer Science subjects from a recognized board.',
                'scope' => 'Students may continue into BS Computer Science, software diplomas, and other undergraduate pathways.',
                'is_active' => true,
                'show_on_website' => true,
                'sort_order' => 1,
            ]
        );

        $undergraduateProgram = AcademicProgram::updateOrCreate(
            ['code' => 'BS-CS'],
            [
                'department_id' => $department->id,
                'name' => 'Bachelor of Science in Computer Science',
                'short_name' => 'BS CS',
                'slug' => 'bs-computer-science',
                'degree_type' => DegreeTypeEnum::BS->value,
                'admission_category' => AdmissionCategoryEnum::Undergraduate->value,
                'duration_years' => 4,
                'total_semesters' => 8,
                'total_credit_hours' => 130,
                'description' => 'Four-year undergraduate computing degree aligned with HEC expectations and software industry demand.',
                'eligibility' => 'Intermediate (FSc / ICS / equivalent) with Mathematics and minimum college merit criteria.',
                'scope' => 'Career pathways in software development, web engineering, teaching, and postgraduate studies.',
                'is_active' => true,
                'show_on_website' => true,
                'sort_order' => 2,
            ]
        );

        $course = Course::updateOrCreate(
            ['code' => 'CS-101'],
            [
                'department_id' => $department->id,
                'academic_program_id' => $undergraduateProgram->id,
                'name' => 'Programming Fundamentals',
                'slug' => 'programming-fundamentals',
                'course_type' => 'core',
                'semester_number' => 1,
                'credit_hours' => 4,
                'theory_hours' => 3,
                'lab_hours' => 1,
                'pre_requisites' => null,
                'is_active' => true,
                'show_on_website' => false,
                'sort_order' => 10,
            ]
        );

        $teacher = Teacher::updateOrCreate(
            ['employee_id' => 'JDCA-T-001'],
            [
                'department_id' => $department->id,
                'employee_id' => 'JDCA-T-001',
                'name' => 'Muhammad Bilal',
                'father_name' => 'Ghulam Nabi',
                'date_of_birth' => '1992-03-10',
                'gender' => GenderEnum::Male->value,
                'religion' => 'Islam',
                'nationality' => 'Pakistani',
                'cnic' => '15202-1234567-1',
                'email' => 'teacher@jdca.edu.pk',
                'phone' => '0311-2345678',
                'address' => 'Eidgah, Astore',
                'city' => 'Astore',
                'province' => 'gilgit_baltistan',
                'highest_qualification' => 'MS / M.Phil',
                'specialization' => 'Software Engineering',
                'qualification_institution' => 'Karakoram International University',
                'qualification_year' => 2018,
                'designation' => 'Lecturer',
                'employment_type' => EmploymentTypeEnum::Permanent->value,
                'experience_years' => 6,
                'joining_date' => '2021-08-15',
                'salary_grade' => 'BPS-17',
                'basic_salary' => 85000,
                'status' => TeacherStatusEnum::Active->value,
                'is_active' => true,
                'remarks' => 'Default minimal faculty profile for testing.',
            ]
        );

        $student = Student::updateOrCreate(
            ['roll_number' => 'JDCA-2026-0001'],
            [
                'department_id' => $department->id,
                'academic_program_id' => $undergraduateProgram->id,
                'academic_year_id' => $academicYear->id,
                'registration_number' => 'REG-2026-0001',
                'name' => 'Ali Raza',
                'father_name' => 'Haider Ali',
                'date_of_birth' => '2007-02-14',
                'gender' => GenderEnum::Male->value,
                'religion' => 'Islam',
                'nationality' => 'Pakistani',
                'domicile' => 'Astore',
                'cnic' => '35202-7654321-3',
                'father_cnic' => '35202-1111111-1',
                'email' => 'student@jdca.edu.pk',
                'phone' => '0321-1234567',
                'father_phone' => '0333-7654321',
                'address' => 'Near Ali Murtaza Chowk, Astore',
                'city' => 'Astore',
                'district' => 'Astore',
                'province' => 'gilgit_baltistan',
                'permanent_address' => 'Astore, Gilgit-Baltistan',
                'batch_year' => 2026,
                'current_semester' => 1,
                'section' => 'A',
                'admission_date' => '2026-08-20',
                'admission_type' => AdmissionTypeEnum::Regular->value,
                'previous_qualification' => 'ICS',
                'previous_marks' => 940,
                'previous_board' => 'BISE Gilgit',
                'previous_year' => 2026,
                'status' => StudentStatusEnum::Active->value,
                'disability' => null,
                'is_hosteler' => false,
                'is_active' => true,
                'remarks' => 'Default minimal student profile for testing.',
            ]
        );

        $feeStructure = FeeStructure::updateOrCreate(
            ['title' => 'BS CS Tuition Fee - Fall 2026'],
            [
                'academic_program_id' => $undergraduateProgram->id,
                'academic_year_id' => $academicYear->id,
                'title' => 'BS CS Tuition Fee - Fall 2026',
                'fee_type' => FeeTypeEnum::Tuition->value,
                'semester_number' => 1,
                'amount' => 28000,
                'late_fine_per_day' => 100,
                'due_date' => '2026-09-15',
                'frequency' => 'semester',
                'is_mandatory' => true,
                'is_active' => true,
                'description' => 'Default tuition fee structure for the seeded BS CS student.',
            ]
        );

        FeePayment::updateOrCreate(
            ['challan_number' => 'CHN-2026-0001'],
            [
                'student_id' => $student->id,
                'fee_structure_id' => $feeStructure->id,
                'academic_year_id' => $academicYear->id,
                'challan_number' => 'CHN-2026-0001',
                'fee_type' => FeeTypeEnum::Tuition->value,
                'semester_number' => 1,
                'amount_due' => 28000,
                'amount_paid' => 28000,
                'fine_amount' => 0,
                'discount_amount' => 0,
                'payment_status' => PaymentStatusEnum::Paid->value,
                'payment_method' => PaymentMethodEnum::BankDraft->value,
                'due_date' => '2026-09-15',
                'payment_date' => '2026-09-10',
                'transaction_id' => 'JDCA-BANK-2026-001',
                'bank_name' => 'HBL Astore Branch',
                'remarks' => 'Paid in full.',
            ]
        );

        $scholarship = Scholarship::updateOrCreate(
            ['name' => 'JDCA Merit Support Scholarship'],
            [
                'slug' => 'jdca-merit-support-scholarship',
                'scholarship_type' => ScholarshipTypeEnum::Merit->value,
                'description' => 'Internal scholarship for high-performing students with strong academic records.',
                'eligibility_criteria' => 'Minimum 85% marks in previous examination and active student status.',
                'funding_source' => 'College Merit Fund',
                'amount' => 15000,
                'coverage_percent' => null,
                'seats' => 5,
                'application_start' => '2026-09-01',
                'application_end' => '2026-10-15',
                'is_recurring' => true,
                'is_active' => true,
            ]
        );

        ScholarshipAward::updateOrCreate(
            [
                'scholarship_id' => $scholarship->id,
                'student_id' => $student->id,
                'academic_year_id' => $academicYear->id,
            ],
            [
                'scholarship_id' => $scholarship->id,
                'student_id' => $student->id,
                'academic_year_id' => $academicYear->id,
                'status' => ScholarshipStatusEnum::Approved->value,
                'amount_awarded' => 15000,
                'application_date' => '2026-09-05',
                'approval_date' => '2026-09-20',
                'disbursement_date' => null,
                'reason' => 'Strong previous academic record and entry merit.',
                'remarks' => 'Award kept as minimal test scholarship.',
            ]
        );

        Announcement::updateOrCreate(
            ['title' => 'Admissions Open for Fall 2026'],
            [
                'title' => 'Admissions Open for Fall 2026',
                'content' => 'Admissions are now open for ICS and BS Computer Science. Submit online applications before the published deadline.',
                'audience' => 'all',
                'priority' => 'high',
                'department_id' => null,
                'is_published' => true,
                'publish_date' => '2026-06-15',
                'expiry_date' => '2026-09-30',
            ]
        );

        NewsArticle::updateOrCreate(
            ['slug' => 'jdca-launches-updated-online-admissions'],
            [
                'title' => 'JDCA Launches Updated Online Admissions',
                'slug' => 'jdca-launches-updated-online-admissions',
                'excerpt' => 'The college website now offers a cleaner online admission journey for intermediate and undergraduate applicants.',
                'content' => 'JDCA has updated its public website, online admissions, and homepage sections so the administration can manage content, appearance, and visibility more easily.',
                'category' => 'news',
                'published_date' => '2026-06-18',
                'is_published' => true,
                'is_featured' => true,
            ]
        );

        WebsiteEvent::updateOrCreate(
            ['slug' => 'jdca-admission-open-house-2026'],
            [
                'title' => 'JDCA Admission Open House 2026',
                'slug' => 'jdca-admission-open-house-2026',
                'description' => 'Parents and students can visit campus, meet faculty, and receive programme guidance.',
                'venue' => 'Main Campus, Astore',
                'start_datetime' => '2026-07-10 10:00:00',
                'end_datetime' => '2026-07-10 14:00:00',
                'is_published' => true,
                'is_featured' => true,
            ]
        );

    }
}
