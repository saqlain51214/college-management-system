<?php

use App\Models\WebsitePage;
use Illuminate\Database\Migrations\Migration;

/**
 * Campus Facilities, Admission Procedure, and Semester Rules moved from
 * hardcoded blade arrays to admin-editable Repeaters that fall back to
 * WebsitePage::defaultContentFor() when the DB field is empty. That fallback
 * makes the public page look identical either way, but leaves the admin
 * Repeater empty until someone edits it — confusing, since there's nothing
 * to click "Edit" on for content that's actually already live. Seed the real
 * default values into the DB now so admin sees (and can edit) exactly what
 * visitors see, instead of a blank repeater silently backed by a fallback.
 */
return new class extends Migration
{
    protected array $map = [
        'campus-facilities'   => 'facilities',
        'admission-procedure' => 'steps',
        'semester-rules'      => 'rule_sections',
    ];

    public function up(): void
    {
        foreach ($this->map as $slug => $key) {
            $page = WebsitePage::where('slug', $slug)->first();

            if (! $page) {
                continue;
            }

            $content = $page->content ?? [];

            // Only fill in if genuinely empty — never overwrite content an admin already edited.
            if (! empty($content[$key])) {
                continue;
            }

            $content[$key] = WebsitePage::defaultContentFor($slug)[$key] ?? [];

            $page->update(['content' => $content]);
        }
    }

    public function down(): void
    {
        // Intentionally left as-is — reverting would delete real admin-editable
        // content that may have been changed since this ran.
    }
};
