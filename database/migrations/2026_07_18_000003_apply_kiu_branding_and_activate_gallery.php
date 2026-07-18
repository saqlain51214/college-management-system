<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Live-data branding updates:
 *  - Remove "School" from the college name (EN + Urdu) and footer copyright
 *  - Switch the theme to KIU-style navy + clean sans fonts (only where the
 *    value still equals a previous default, so admin choices are preserved)
 *  - Activate the Campus Gallery page in the public menu
 */
return new class extends Migration
{
    public function up(): void
    {
        if (DB::getSchemaBuilder()->hasTable('college_settings')) {
            $set = fn (string $key, string $value) => DB::table('college_settings')
                ->where('key', $key)->update(['value' => $value]);

            $set('college_name', 'Jinnah Degree College Astore');
            $set('college_name_urdu', 'جناح ڈگری کالج آستور');
            $set('website_footer_copyright', 'Jinnah Degree College Astore. All rights reserved.');

            // Theme → navy (override maroon or emerald, but not a custom colour)
            DB::table('college_settings')->where('key', 'website_theme_brand')
                ->whereIn('value', ['#6B2D39', '#6b2d39', '#0F766E', '#0f766e'])
                ->update(['value' => '#1A3A5F']);
            DB::table('college_settings')->where('key', 'website_theme_brand_dark')
                ->whereIn('value', ['#5A2430', '#5a2430', '#115E59', '#115e59'])
                ->update(['value' => '#122A45']);

            // Fonts → clean sans (only from previous defaults)
            DB::table('college_settings')->where('key', 'website_font_sans')
                ->whereIn('value', ['open-sans'])->update(['value' => 'inter']);
            DB::table('college_settings')->where('key', 'website_font_display')
                ->whereIn('value', ['playfair-display'])->update(['value' => 'inter']);
        }

        if (DB::getSchemaBuilder()->hasTable('website_pages')) {
            DB::table('website_pages')->where('slug', 'gallery')
                ->update(['in_menu' => true, 'is_published' => true]);
        }
    }

    public function down(): void
    {
        // Non-destructive branding change; no rollback.
    }
};
