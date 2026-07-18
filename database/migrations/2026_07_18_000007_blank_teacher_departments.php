<?php

use Database\Seeders\JdcaTeachersSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Re-run the faculty upsert (which now leaves department blank) so any teachers
 * previously imported with a subject-based department are reset to no department.
 * The college will assign each one from the dashboard.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! DB::getSchemaBuilder()->hasTable('teachers')) {
            return;
        }

        JdcaTeachersSeeder::upsertAll();
    }

    public function down(): void
    {
        // No rollback — department assignment is managed in the dashboard.
    }
};
