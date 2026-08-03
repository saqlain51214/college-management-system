<?php

namespace Tests\Feature;

use App\Models\AcademicProgram;
use Database\Seeders\JdcaProgramsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * PublicController::programs() (and the home page teaser) used to query
 * AcademicProgram::active() — is_active only — instead of ::visible()
 * (is_active AND show_on_website). JdcaProgramsSeeder marks any leftover,
 * non-canonical programme as show_on_website=false specifically so it
 * won't appear publicly, but the ::active() scope ignored that flag, so
 * stale/dummy programmes (e.g. an old "Bachelor of Education" record) kept
 * showing up on both the /programs listing and the homepage.
 */
class PublicProgramsVisibilityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(JdcaProgramsSeeder::class);
    }

    public function test_programs_listing_page_hides_non_canonical_programmes(): void
    {
        $department = AcademicProgram::where('slug', 'associate-degree-in-computer-science')->value('department_id');

        $stale = AcademicProgram::create([
            'department_id' => $department,
            'name' => 'Bachelor of Education',
            'slug' => 'stale-bachelor-of-education',
            'degree_type' => \App\Enums\DegreeTypeEnum::BEd->value,
            'is_active' => true,
            'show_on_website' => false,
        ]);

        $response = $this->get(route('programs'));

        $response->assertOk();
        $response->assertSee('Associate Degree in Computer Science');
        $response->assertDontSee('Bachelor of Education');
    }

    public function test_home_page_programme_teaser_hides_non_canonical_programmes(): void
    {
        $department = AcademicProgram::where('slug', 'associate-degree-in-computer-science')->value('department_id');

        AcademicProgram::create([
            'department_id' => $department,
            'name' => 'Master of Education',
            'slug' => 'stale-master-of-education',
            'degree_type' => \App\Enums\DegreeTypeEnum::BEd->value,
            'is_active' => true,
            'show_on_website' => false,
        ]);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertDontSee('Master of Education');
    }
}
