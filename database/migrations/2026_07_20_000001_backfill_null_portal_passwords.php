<?php

use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\Migrations\Migration;

/**
 * Safety net: any student/teacher that somehow has NO portal password (e.g.
 * created before the initial-password hook existed) gets the initial password
 * "123456", so nobody is locked out after the default-password fallback was
 * removed. Accounts that already have a password are left untouched.
 */
return new class extends Migration
{
    public function up(): void
    {
        Student::query()->whereNull('portal_password')->chunkById(200, function ($students) {
            foreach ($students as $student) {
                $student->portal_password = '123456'; // model mutator hashes it
                $student->save();
            }
        });

        Teacher::query()->whereNull('portal_password')->chunkById(200, function ($teachers) {
            foreach ($teachers as $teacher) {
                $teacher->portal_password = '123456';
                $teacher->save();
            }
        });
    }

    public function down(): void
    {
        // No-op.
    }
};
