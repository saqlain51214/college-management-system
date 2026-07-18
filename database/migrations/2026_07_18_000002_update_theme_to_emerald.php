<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Switch the website theme from maroon to emerald + gold.
 * Only updates rows still holding the old maroon defaults, so any colour an
 * admin later picks in Settings → Website Appearance is never overwritten.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! DB::getSchemaBuilder()->hasTable('college_settings')) {
            return;
        }

        DB::table('college_settings')->where('key', 'website_theme_brand')
            ->whereIn('value', ['#6B2D39', '#6b2d39'])->update(['value' => '#0F766E']);

        DB::table('college_settings')->where('key', 'website_theme_brand_dark')
            ->whereIn('value', ['#5A2430', '#5a2430'])->update(['value' => '#115E59']);
    }

    public function down(): void
    {
        if (! DB::getSchemaBuilder()->hasTable('college_settings')) {
            return;
        }

        DB::table('college_settings')->where('key', 'website_theme_brand')
            ->where('value', '#0F766E')->update(['value' => '#6B2D39']);

        DB::table('college_settings')->where('key', 'website_theme_brand_dark')
            ->where('value', '#115E59')->update(['value' => '#5A2430']);
    }
};
