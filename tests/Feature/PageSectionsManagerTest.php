<?php

namespace Tests\Feature;

use App\Filament\Pages\AboutUsSections;
use App\Filament\Pages\AcademicsSections;
use App\Filament\Pages\AdmissionSections;
use App\Filament\Pages\HomePageSections;
use App\Models\LeadershipMessage;
use App\Models\User;
use App\Models\WebsitePage;
use Database\Seeders\WebsitePageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Covers the 4 Website Pages "Sections" screens — the real implementation of
 * the mockup: rename (menu_label) and toggle (is_published) work, and each
 * page loads without error even with the app's other resources/pages hidden
 * from navigation.
 */
class PageSectionsManagerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
        $this->admin = User::factory()->create();
        $this->admin->assignRole('super_admin');

        $this->seed(WebsitePageSeeder::class);
    }

    public function test_all_four_sections_pages_load(): void
    {
        foreach ([HomePageSections::class, AboutUsSections::class, AcademicsSections::class, AdmissionSections::class] as $page) {
            Livewire::actingAs($this->admin)->test($page)->assertOk();
        }
    }

    public function test_renaming_a_section_updates_menu_label_without_touching_the_slug(): void
    {
        Livewire::actingAs($this->admin)
            ->test(AboutUsSections::class)
            ->call('renameSection', 'about-mission', 'Our Mission');

        $page = WebsitePage::where('slug', 'about-mission')->first();
        $this->assertSame('Our Mission', $page->menu_label);
        $this->assertSame('about-mission', $page->slug);
    }

    public function test_toggling_a_section_flips_is_published(): void
    {
        $page = WebsitePage::where('slug', 'campus-facilities')->first();
        $this->assertTrue((bool) $page->is_published);

        Livewire::actingAs($this->admin)
            ->test(AboutUsSections::class)
            ->call('toggleSection', 'campus-facilities');

        $this->assertFalse((bool) $page->refresh()->is_published);
    }

    public function test_home_sections_lists_leadership_messages_as_a_cross_link_collection(): void
    {
        LeadershipMessage::create(['name' => 'Test VC', 'designation' => 'Vice Chancellor', 'message' => 'Hi', 'sort_order' => 1, 'is_active' => true]);

        Livewire::actingAs($this->admin)
            ->test(HomePageSections::class)
            ->assertSee('Leadership Messages')
            ->assertSee('Managed under About Us');
    }

    public function test_academics_sections_shows_department_and_program_counts(): void
    {
        \App\Models\Department::create(['name' => 'Dept A', 'slug' => 'dept-a', 'type' => 'academic']);

        Livewire::actingAs($this->admin)
            ->test(AcademicsSections::class)
            ->assertSee('Departments')
            ->assertSee('Academic Programs');
    }
}
