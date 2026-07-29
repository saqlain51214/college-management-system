<?php

namespace Tests\Feature;

use App\Models\HeroSlide;
use App\Models\WebsitePage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

/**
 * Covers the one-time move of Hero Slides from the old WebsitePage JSON
 * blob into the new dedicated hero_slides table.
 */
class HeroSlideMigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_migrates_slides_from_the_home_page_json_into_hero_slides_table(): void
    {
        WebsitePage::create([
            'slug' => 'home',
            'title' => 'Home',
            'content' => [
                'hero' => [
                    'slides' => [
                        ['title' => 'Welcome', 'description' => 'First slide', 'image' => 'assets/images/default/slider-1.jpeg', 'primary_btn_text' => 'Apply Now', 'primary_btn_link' => 'admissions'],
                        ['title' => 'Programmes', 'description' => 'Second slide', 'image' => 'website/pages/uploaded.jpg'],
                    ],
                ],
            ],
        ]);

        Artisan::call('hero-slides:migrate-from-page');

        $this->assertEquals(2, HeroSlide::count());
        $first = HeroSlide::where('title', 'Welcome')->firstOrFail();
        $this->assertEquals(0, $first->sort_order);
        $this->assertEquals('admissions', $first->primary_btn_link);
        $this->assertTrue($first->is_active);
        $this->assertStringContainsString('assets/images/default/slider-1.jpeg', $first->image_url);

        $second = HeroSlide::where('title', 'Programmes')->firstOrFail();
        $this->assertStringContainsString('storage/website/pages/uploaded.jpg', $second->image_url);
    }

    public function test_does_not_duplicate_on_a_second_run_without_force(): void
    {
        WebsitePage::create([
            'slug' => 'home', 'title' => 'Home',
            'content' => ['hero' => ['slides' => [['title' => 'Welcome', 'image' => 'assets/x.jpg']]]],
        ]);

        Artisan::call('hero-slides:migrate-from-page');
        Artisan::call('hero-slides:migrate-from-page');

        $this->assertEquals(1, HeroSlide::count());
    }

    public function test_does_nothing_when_no_slides_exist_in_the_home_page(): void
    {
        WebsitePage::create(['slug' => 'home', 'title' => 'Home', 'content' => []]);

        Artisan::call('hero-slides:migrate-from-page');

        $this->assertEquals(0, HeroSlide::count());
    }
}
