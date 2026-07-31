<?php

namespace Tests\Feature;

use App\Models\HomeSection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * "Elevate Your Learning" / "Campus Life" / "Testimonials" used to be
 * editable in the admin panel but rendered nowhere on the live site. Now
 * wired into the homepage (resources/views/public/home.blade.php) — this
 * confirms they actually show up, and that turning one off actually hides it.
 */
class HomeSectionsRenderTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_sections_render_on_the_live_homepage_by_default(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('id="testimonials"', false);
    }

    public function test_an_inactive_home_section_is_hidden_from_the_homepage(): void
    {
        HomeSection::create(['key' => 'testimonials', 'title' => 'Testimonials', 'is_active' => false]);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertDontSee('id="testimonials"', false);
    }
}
