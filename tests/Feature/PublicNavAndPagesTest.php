<?php

namespace Tests\Feature;

use App\Models\LeadershipMessage;
use App\Models\WebsitePage;
use Database\Seeders\WebsitePageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Covers the Website Management overhaul: no more duplicate/dead nav links,
 * an admin's menu_label actually changes what's shown, leadership messages
 * drive the About Us submenu dynamically, and the two pages whose content
 * moved into admin-editable Repeaters (Campus Facilities, Admission
 * Procedure) still render correctly from that content.
 */
class PublicNavAndPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(WebsitePageSeeder::class);
    }

    public function test_home_page_nav_has_no_duplicate_scholarship_links_and_includes_programs(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee(route('scholarships.show', 'merit'), false);
        $response->assertSee(route('scholarships.show', 'need'), false);
        $response->assertSee(route('scholarships.show', 'orphan'), false);
        $response->assertSee(route('scholarships.show', 'special'), false);
        $response->assertSee(route('programs'), false);
    }

    public function test_about_us_submenu_lists_leadership_messages_dynamically(): void
    {
        LeadershipMessage::create(['name' => 'Dr. Test VC', 'designation' => 'Vice Chancellor', 'message' => 'Hello', 'sort_order' => 1, 'is_active' => true]);
        $second = LeadershipMessage::create(['name' => 'Test Principal', 'designation' => 'Principal', 'message' => 'Hi', 'sort_order' => 2, 'is_active' => true]);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Message from Vice Chancellor');
        $response->assertSee('Message from Principal');
        $response->assertSee(route('leadership.message', $second), false);
    }

    public function test_renaming_a_page_menu_label_changes_the_nav_text_without_changing_its_url(): void
    {
        $page = WebsitePage::where('slug', 'contact')->first();
        $page->update(['menu_label' => 'Get In Touch']);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Get In Touch');
        $response->assertSee(route('contact'), false);
    }

    public function test_campus_facilities_page_renders_admin_edited_facilities(): void
    {
        $page = WebsitePage::where('slug', 'campus-facilities')->first();
        $page->update(['content' => ['facilities' => [
            ['title' => 'Robotics Lab', 'description' => 'A brand new robotics lab.', 'icon' => 'computer'],
        ]]]);

        $response = $this->get(route('campus-facilities'));

        $response->assertOk();
        $response->assertSee('Robotics Lab');
    }

    public function test_admission_procedure_page_renders_admin_edited_steps(): void
    {
        $page = WebsitePage::where('slug', 'admission-procedure')->first();
        $page->update(['content' => ['steps' => [
            ['title' => 'Custom Step One', 'description' => 'A custom first step.'],
        ]]]);

        $response = $this->get(route('admissions.procedure'));

        $response->assertOk();
        $response->assertSee('Custom Step One');
    }
}
