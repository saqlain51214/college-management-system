<?php

use App\Models\Teacher;
use Illuminate\Database\Migrations\Migration;

/**
 * Give every existing teacher the default portal password "123456" (same as
 * students), so staff can log into the teacher portal out of the box.
 * Teachers who later set their own password are not affected.
 */
return new class extends Migration
{
    public function up(): void
    {
        Teacher::query()->chunkById(200, function ($teachers) {
            foreach ($teachers as $teacher) {
                // The model's mutator hashes the value on save.
                $teacher->portal_password = '123456';
                $teacher->save();
            }
        });
    }

    public function down(): void
    {
        // No-op: passwords are not reverted.
    }
};
