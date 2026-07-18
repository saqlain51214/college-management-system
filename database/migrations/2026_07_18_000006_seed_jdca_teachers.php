<?php

use Database\Seeders\JdcaTeachersSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Add the JDCA faculty list to the live database (idempotent — upserts by
 * employee_id). Uses the same source as the seeder so fresh installs and
 * existing deployments stay identical.
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
        if (! DB::getSchemaBuilder()->hasTable('teachers')) {
            return;
        }

        $ids = collect(JdcaTeachersSeeder::teachers())->map(fn ($t) => $t[0])->all();
        DB::table('teachers')->whereIn('employee_id', $ids)->delete();
    }
};
