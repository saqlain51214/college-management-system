<?php

namespace App\Console\Commands;

use App\Models\HeroSlide;
use App\Models\WebsitePage;
use Illuminate\Console\Command;

/**
 * One-time move of the home page's "Hero Slides" from the old JSON blob
 * (WebsitePage.content['hero']['slides'], edited via the Home Page Content
 * repeater) into the new dedicated hero_slides table/admin page — run once
 * after deploying the Homepage Slider module, then the old repeater field
 * can be safely removed from the admin form.
 */
class MigrateHeroSlidesFromWebsitePage extends Command
{
    protected $signature = 'hero-slides:migrate-from-page {--force : Import even if hero_slides already has rows}';
    protected $description = 'Copy existing Home Page "Hero Slides" JSON data into the new hero_slides table';

    public function handle(): int
    {
        if (HeroSlide::count() > 0 && ! $this->option('force')) {
            $this->warn('hero_slides already has ' . HeroSlide::count() . ' row(s) — skipping to avoid duplicates. Pass --force to import anyway.');
            return self::SUCCESS;
        }

        $page = WebsitePage::where('slug', 'home')->first();
        $slides = $page?->content['hero']['slides'] ?? [];

        if (empty($slides)) {
            $this->info('No Hero Slides found in the Home Page content — nothing to migrate.');
            return self::SUCCESS;
        }

        $created = 0;
        foreach (array_values($slides) as $i => $slide) {
            if (empty($slide['title']) && empty($slide['image'])) {
                continue;
            }

            HeroSlide::create([
                'image'              => $slide['image'] ?? '',
                'title'              => $slide['title'] ?? 'Untitled Slide',
                'description'        => $slide['description'] ?? null,
                'primary_btn_text'   => $slide['primary_btn_text'] ?? null,
                'primary_btn_link'   => $slide['primary_btn_link'] ?? null,
                'secondary_btn_text' => $slide['secondary_btn_text'] ?? null,
                'secondary_btn_link' => $slide['secondary_btn_link'] ?? null,
                'sort_order'         => $i,
                'is_active'          => true,
            ]);
            $created++;
        }

        $this->info("Migrated {$created} hero slide(s) into the new Homepage Slider module.");
        $this->info('You can now review/reorder them at Website Management → Homepage Slider.');

        return self::SUCCESS;
    }
}
