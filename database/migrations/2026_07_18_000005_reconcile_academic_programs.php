<?php

use App\Models\AcademicProgram;
use App\Models\Department;
use Database\Seeders\JdcaProgramsSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Reconcile academic programmes to the authoritative admission list so the
 * navbar Academics menu and the online admission form show the SAME set.
 * Non-destructive: extra programmes are hidden (show_on_website = false),
 * not deleted, so an admin can still see/restore them.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! DB::getSchemaBuilder()->hasTable('academic_programs')) {
            return;
        }

        // Hide everything first…
        AcademicProgram::query()->update(['show_on_website' => false]);

        // …then create/enable exactly the authoritative list.
        foreach (JdcaProgramsSeeder::programmes() as $p) {
            $departmentId = Department::where('slug', $p['dept'])->value('id');
            if (! $departmentId) {
                continue;
            }

            AcademicProgram::updateOrCreate(
                ['slug' => $p['slug']],
                [
                    'department_id'   => $departmentId,
                    'name'            => $p['name'],
                    'short_name'      => $p['short_name'],
                    'code'            => $p['code'],
                    'degree_type'     => $p['degree_type'],
                    'duration_years'  => 2,
                    'total_semesters' => 4,
                    'is_active'       => true,
                    'show_on_website' => true,
                    'sort_order'      => $p['sort_order'],
                ]
            );
        }
    }

    public function down(): void
    {
        // Non-destructive reconciliation; no rollback.
    }
};
