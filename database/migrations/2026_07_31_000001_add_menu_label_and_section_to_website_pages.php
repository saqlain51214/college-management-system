<?php

use App\Models\WebsitePage;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * `menu_label` lets an admin rename what visitors see in the nav without
 * touching `title` (used elsewhere, e.g. page headings) or `slug` (baked
 * into route definitions — changing it would 404 the page). `section`
 * groups the admin's Website Pages table to mirror the public nav tree
 * (Home / About Us / Academics / Admission / Other) instead of one long
 * flat list.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('website_pages', function (Blueprint $table) {
            $table->string('menu_label', 255)->nullable()->after('title');
            $table->string('section', 50)->default('Other')->after('slug');
        });

        // Backfill `section` for rows that already exist in production — done here
        // (not via re-running WebsitePageSeeder) because the seeder also overwrites
        // `content`, which would wipe out any live admin edits.
        foreach (WebsitePage::STATIC_PAGES as $slug => $definition) {
            DB::table('website_pages')->where('slug', $slug)->update(['section' => $definition['section']]);
        }
    }

    public function down(): void
    {
        Schema::table('website_pages', function (Blueprint $table) {
            $table->dropColumn(['menu_label', 'section']);
        });
    }
};
