<?php

namespace Tests\Feature\Public;

use App\Models\AcademicProgram;
use App\Models\Announcement;
use App\Models\NewsArticle;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    // ── Home ─────────────────────────────────────────────────────────────────

    public function test_home_page_loads(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    // ── About ─────────────────────────────────────────────────────────────────

    public function test_about_page_loads(): void
    {
        $response = $this->get('/about');
        $response->assertStatus(200);
    }

    // ── Programs (was failing: orderBy('level') column missing) ───────────────

    public function test_programs_page_loads_without_programs(): void
    {
        $response = $this->get('/programs');
        $response->assertStatus(200);
    }

    public function test_programs_page_shows_active_programs(): void
    {
        AcademicProgram::factory()->create([
            'name'      => 'BS Computer Science',
            'is_active' => true,
            'sort_order' => 1,
        ]);
        AcademicProgram::factory()->create([
            'name'      => 'BS English',
            'is_active' => false,
            'sort_order' => 2,
        ]);

        $response = $this->get('/programs');

        $response->assertStatus(200);
        $response->assertSee('BS Computer Science');
        $response->assertDontSee('BS English');
    }

    public function test_programs_page_orders_by_sort_order_not_level(): void
    {
        // Regression test: previously used orderBy('level') which caused SQLSTATE error
        AcademicProgram::factory()->count(3)->create(['is_active' => true]);

        $response = $this->get('/programs');
        $response->assertStatus(200);
    }

    // ── Faculty ───────────────────────────────────────────────────────────────

    public function test_faculty_page_loads(): void
    {
        $response = $this->get('/faculty');
        $response->assertStatus(200);
    }

    // ── Admissions (was failing: orderBy('level') column missing) ─────────────

    public function test_admissions_page_loads_without_programs(): void
    {
        $response = $this->get('/admissions');
        $response->assertStatus(200);
    }

    public function test_admissions_page_orders_by_sort_order_not_level(): void
    {
        // Regression test: previously used orderBy('level') which caused SQLSTATE error
        AcademicProgram::factory()->count(3)->create(['is_active' => true]);

        $response = $this->get('/admissions');
        $response->assertStatus(200);
    }

    // ── Contact ───────────────────────────────────────────────────────────────

    public function test_contact_page_loads(): void
    {
        $response = $this->get('/contact');
        $response->assertStatus(200);
    }

    // ── News ──────────────────────────────────────────────────────────────────

    public function test_news_page_loads(): void
    {
        $response = $this->get('/news');
        $response->assertStatus(200);
    }

    public function test_news_page_shows_published_articles(): void
    {
        NewsArticle::factory()->create(['title' => 'Published News', 'is_published' => true, 'published_date' => now()]);
        NewsArticle::factory()->create(['title' => 'Draft News',     'is_published' => false]);

        $response = $this->get('/news');

        $response->assertStatus(200);
        $response->assertSee('Published News');
        $response->assertDontSee('Draft News');
    }

    public function test_news_detail_page_loads(): void
    {
        $article = NewsArticle::factory()->create([
            'title'          => 'Test Article',
            'slug'           => 'test-article',
            'is_published'   => true,
            'published_date' => now(),
        ]);

        $response = $this->get('/news/' . $article->slug);
        $response->assertStatus(200);
        $response->assertSee('Test Article');
    }

    public function test_news_detail_returns_404_for_unpublished(): void
    {
        $article = NewsArticle::factory()->create([
            'slug'         => 'unpublished-article',
            'is_published' => false,
        ]);

        $response = $this->get('/news/unpublished-article');
        $response->assertStatus(404);
    }

    // ── Notices ───────────────────────────────────────────────────────────────

    public function test_notices_page_loads(): void
    {
        $response = $this->get('/notices');
        $response->assertStatus(200);
    }

    public function test_notices_page_shows_published_notices(): void
    {
        Announcement::factory()->create([
            'title'        => 'Active Notice',
            'is_published' => true,
            'publish_date' => now(),
            'expiry_date'  => null,
        ]);
        Announcement::factory()->create([
            'title'        => 'Expired Notice',
            'is_published' => true,
            'publish_date' => now()->subDays(5),
            'expiry_date'  => now()->subDay(),
        ]);

        $response = $this->get('/notices');

        $response->assertStatus(200);
        $response->assertSee('Active Notice');
        $response->assertDontSee('Expired Notice');
    }

    // ── Results ───────────────────────────────────────────────────────────────

    public function test_results_page_loads(): void
    {
        $response = $this->get('/results');
        $response->assertStatus(200);
    }

    public function test_results_page_shows_error_for_unknown_roll_number(): void
    {
        $response = $this->get('/results?roll_number=UNKNOWN-9999');

        $response->assertStatus(200);
        $response->assertSee('No student found');
    }

    public function test_results_page_finds_student_by_roll_number(): void
    {
        $student = Student::factory()->create([
            'roll_number' => 'CS-TEST-001',
            'name'        => 'Test Student Name',
        ]);

        $response = $this->get('/results?roll_number=CS-TEST-001');

        $response->assertStatus(200);
        $response->assertSee('Test Student Name');
    }
}
