<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Make the footer background match the navy top bar for a cohesive look.
 * Only updates the row if it still holds the old near-black default.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! DB::getSchemaBuilder()->hasTable('college_settings')) {
            return;
        }

        DB::table('college_settings')->where('key', 'website_theme_footer_bg')
            ->whereIn('value', ['#1A1A1A', '#1a1a1a'])
            ->update(['value' => '#1A3A5F']);
    }

    public function down(): void
    {
        if (! DB::getSchemaBuilder()->hasTable('college_settings')) {
            return;
        }

        DB::table('college_settings')->where('key', 'website_theme_footer_bg')
            ->where('value', '#1A3A5F')->update(['value' => '#1A1A1A']);
    }
};
