<?php

namespace Tests\Feature;

use App\Models\WebsitePage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pages like Semester Rules moved to admin-editable Repeaters that fall back
 * to WebsitePage::defaultContentFor() when the DB content is empty — but
 * that left the admin Repeater looking empty for pages created before those
 * keys existed. The 2026_07_31_000003 migration backfills the real defaults
 * into content so admin sees (and can edit) what's actually live.
 */
class BackfillRepeaterContentMigrationTest extends TestCase
{
    use RefreshDatabase;

    protected function runBackfillMigration(): void
    {
        $migration = require database_path('migrations/2026_07_31_000003_backfill_repeater_content_defaults.php');
        $migration->up();
    }

    public function test_backfills_rule_sections_when_missing(): void
    {
        $page = WebsitePage::updateOrCreate(
            ['slug' => 'semester-rules'],
            ['title' => 'Semester Rules', 'section' => 'Academics', 'content' => ['intro_title' => 'Semester Rules & Regulations'], 'is_published' => true],
        );

        $this->runBackfillMigration();

        $this->assertNotEmpty($page->refresh()->content['rule_sections'] ?? null);
        $this->assertSame('Attendance Policy', $page->content['rule_sections'][0]['title']);
    }

    public function test_does_not_overwrite_existing_rule_sections(): void
    {
        $page = WebsitePage::updateOrCreate(
            ['slug' => 'semester-rules'],
            ['title' => 'Semester Rules', 'section' => 'Academics', 'content' => ['rule_sections' => [['title' => 'My Custom Section', 'rules' => ['A custom rule.']]]], 'is_published' => true],
        );

        $this->runBackfillMigration();

        $this->assertSame('My Custom Section', $page->refresh()->content['rule_sections'][0]['title']);
    }
}
