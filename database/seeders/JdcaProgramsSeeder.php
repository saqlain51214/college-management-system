<?php

namespace Database\Seeders;

use App\Enums\DegreeTypeEnum;
use App\Models\AcademicProgram;
use App\Models\Department;
use Illuminate\Database\Seeder;

/**
 * Seeds JDCA departments (via DepartmentSeeder) and the exact programme list
 * offered for admission — the single source of truth for both the public
 * Academics navbar menu and the online admission form's programme dropdown.
 */
class JdcaProgramsSeeder extends Seeder
{
    /**
     * Authoritative programme list (matches the admission form exactly).
     *
     * @return array<int,array<string,mixed>>
     */
    public static function programmes(): array
    {
        return [
            // Department of Education
            ['dept' => 'department-of-education', 'name' => 'Associate Degree in Education', 'short_name' => 'ADE', 'slug' => 'associate-degree-in-education', 'code' => 'ADE', 'degree_type' => DegreeTypeEnum::ADP->value, 'sort_order' => 1],
            ['dept' => 'department-of-education', 'name' => 'B.Ed 1.5 Year', 'short_name' => 'B.Ed (1.5 yr)', 'slug' => 'bed-1-5-year', 'code' => 'BED-1.5', 'degree_type' => DegreeTypeEnum::BEd->value, 'sort_order' => 2],
            ['dept' => 'department-of-education', 'name' => 'B.Ed 2.5 Year', 'short_name' => 'B.Ed (2.5 yr)', 'slug' => 'bed-2-5-year', 'code' => 'BED-2.5', 'degree_type' => DegreeTypeEnum::BEd->value, 'sort_order' => 3],

            // Other departments — one Associate Degree each
            ['dept' => 'department-of-physical-education', 'name' => 'Associate Degree in Health & Physical Education', 'short_name' => 'AD HPE', 'slug' => 'associate-degree-in-health-physical-education', 'code' => 'ADHPE', 'degree_type' => DegreeTypeEnum::ADP->value, 'sort_order' => 1],
            ['dept' => 'department-of-sociology', 'name' => 'Associate Degree in Sociology', 'short_name' => 'ADS', 'slug' => 'associate-degree-in-sociology', 'code' => 'ADS', 'degree_type' => DegreeTypeEnum::ADP->value, 'sort_order' => 1],
            ['dept' => 'department-of-computer-science', 'name' => 'Associate Degree in Computer Science', 'short_name' => 'ADCS', 'slug' => 'associate-degree-in-computer-science', 'code' => 'ADCS', 'degree_type' => DegreeTypeEnum::ADP->value, 'sort_order' => 1],
            ['dept' => 'department-of-english', 'name' => 'Associate Degree in English', 'short_name' => 'AD English', 'slug' => 'associate-degree-in-english', 'code' => 'ADENG', 'degree_type' => DegreeTypeEnum::ADP->value, 'sort_order' => 1],
            ['dept' => 'department-of-continuous-education', 'name' => 'Associate Degree in Continuous Education', 'short_name' => 'ADCE', 'slug' => 'associate-degree-in-continuous-education', 'code' => 'ADCE', 'degree_type' => DegreeTypeEnum::ADP->value, 'sort_order' => 1],
        ];
    }

    public function run(): void
    {
        $this->call(DepartmentSeeder::class);

        foreach (self::programmes() as $p) {
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

        // Any other programmes (from older seed data) should not appear publicly.
        AcademicProgram::whereNotIn('slug', collect(self::programmes())->pluck('slug'))
            ->update(['show_on_website' => false]);
    }
}
