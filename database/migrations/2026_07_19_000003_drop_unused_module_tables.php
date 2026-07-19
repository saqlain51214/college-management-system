<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Drops the tables for modules that were removed from the system
 * (Exams, Attendance, Library, Timetable, LMS) — these had no admin/portal
 * UI and are no longer part of the application.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Child tables (with foreign keys) first, then parents.
        foreach ([
            'exam_results',
            'attendance_records',
            'book_issues',
            'lms_submissions',
            'lms_queries',
            'lms_assignments',
            'lms_materials',
            'exams',
            'attendance_sessions',
            'books',
            'timetables',
        ] as $table) {
            Schema::dropIfExists($table);
        }
    }

    public function down(): void
    {
        // Irreversible: these modules were removed from the codebase. Restore
        // from version control history if they are ever needed again.
    }
};
