<?php

namespace Database\Seeders;

use App\Models\AcademicProgram;
use App\Models\AcademicYear;
use App\Models\CourseOutline;
use App\Models\Department;
use App\Models\FeeStructure;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

/**
 * Seeds SAMPLE course outlines and fee structures so the new modules are not
 * empty. Idempotent — only seeds when the respective table is empty, so it
 * never overwrites real data the college adds.
 */
class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        $samplePdf = 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf';

        // ── Sample course outlines (per department, semesters 1 & 2) ──────────
        if (Schema::hasTable('course_outlines') && CourseOutline::count() === 0) {
            foreach (Department::query()->orderBy('sort_order')->limit(6)->get() as $dept) {
                $programId = $dept->academicPrograms()->value('id');
                foreach ([1, 2] as $sem) {
                    CourseOutline::create([
                        'department_id'       => $dept->id,
                        'academic_program_id' => $programId,
                        'semester_number'     => $sem,
                        'title'               => "{$dept->name} — Semester {$sem} Course Outline",
                        'external_url'        => $samplePdf,
                        'description'         => "Sample outline for Semester {$sem}. Replace with the official PDF from the admin panel.",
                        'sort_order'          => $sem,
                        'is_active'           => true,
                    ]);
                }
            }
        }

        // ── Sample fee structures (per programme) ─────────────────────────────
        if (Schema::hasTable('fee_structures') && FeeStructure::count() === 0) {
            $yearId = Schema::hasTable('academic_years') ? AcademicYear::orderByDesc('name')->value('id') : null;

            foreach (AcademicProgram::query()->limit(6)->get() as $prog) {
                FeeStructure::create([
                    'academic_program_id' => $prog->id,
                    'academic_year_id'    => $yearId,
                    'title'               => "{$prog->name} — Tuition Fee (Semester)",
                    'fee_type'            => 'tuition',
                    'semester_number'     => 1,
                    'amount'              => 25000,
                    'late_fine_per_day'   => 50,
                    'due_date'            => now()->addDays(30)->toDateString(),
                    'frequency'           => 'semester',
                    'is_mandatory'        => true,
                    'is_active'           => true,
                    'description'         => 'Sample tuition fee. Edit or delete from the admin panel.',
                ]);
                FeeStructure::create([
                    'academic_program_id' => $prog->id,
                    'academic_year_id'    => $yearId,
                    'title'               => "{$prog->name} — Admission Fee (One-time)",
                    'fee_type'            => 'admission',
                    'amount'              => 10000,
                    'frequency'           => 'one_time',
                    'is_mandatory'        => true,
                    'is_active'           => true,
                    'description'         => 'Sample admission fee. Edit or delete from the admin panel.',
                ]);
            }
        }
    }
}
