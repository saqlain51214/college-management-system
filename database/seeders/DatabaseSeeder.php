<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            // Core reference data (no FK dependencies)
            DepartmentSeeder::class,
            AcademicProgramSeeder::class,
            AcademicYearSeeder::class,
            CourseSeeder::class,

            // People
            StudentSeeder::class,
            TeacherSeeder::class,

            // Module 7 — Fee Management
            FeeStructureSeeder::class,
            FeePaymentSeeder::class,

            // Module 8 — Attendance
            AttendanceSeeder::class,

            // Module 9 — Examinations
            ExamSeeder::class,
            ExamResultSeeder::class,

            // Module 10 — Scholarships
            ScholarshipSeeder::class,
            ScholarshipAwardSeeder::class,

            // Module 11 — Library
            BookSeeder::class,
            BookIssueSeeder::class,

            // Module 12 — LMS Portal
            LmsMaterialSeeder::class,
            LmsAssignmentSeeder::class,

            // Module 13 — Announcements
            AnnouncementSeeder::class,

            // Module 14 — Website CMS
            NewsArticleSeeder::class,
            WebsitePageSeeder::class,
            WebsiteEventSeeder::class,

            // Timetable
            TimetableSeeder::class,
        ]);
    }
}
