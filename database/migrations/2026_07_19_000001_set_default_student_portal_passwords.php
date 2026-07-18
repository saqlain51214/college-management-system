<?php

use App\Models\Student;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Give every student a default portal password (123456) so they can log in with
 * their Registration Number. Only fills students without a password set, so any
 * custom password a student later sets is preserved.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('students')) {
            return;
        }

        Student::withTrashed()
            ->where(fn ($q) => $q->whereNull('portal_password')->orWhere('portal_password', ''))
            ->chunkById(200, function ($students) {
                foreach ($students as $student) {
                    $student->portal_password = '123456'; // hashed by the model mutator
                    $student->saveQuietly();
                }
            });
    }

    public function down(): void
    {
        // Passwords are managed in the portal; no rollback.
    }
};
