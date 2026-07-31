<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * The "College Gallery" page was seeded with 6 stock placeholder photos —
 * these were what actually showed live on the public Gallery page until an
 * admin replaced them by hand. Clears them so the page starts empty and
 * only ever shows real photos an admin uploads via Website Pages → Gallery.
 */
return new class extends Migration
{
    public function up(): void
    {
        $page = DB::table('website_pages')->where('slug', 'gallery')->first();

        if (! $page || ! $page->content) {
            return;
        }

        $content = json_decode($page->content, true) ?: [];
        $content['gallery_items'] = [];

        DB::table('website_pages')->where('slug', 'gallery')->update([
            'content' => json_encode($content),
        ]);
    }

    public function down(): void
    {
        // Not reversible — the original stock placeholder photos aren't worth restoring.
    }
};
