<?php

namespace App\Support;

/**
 * Strips HTML/script tags from public-submitted free-text fields (contact
 * message, job application cover letter, admission remarks) before saving —
 * defense-in-depth on top of Blade's own output escaping. Deliberately not a
 * Composer dependency (e.g. mews/purifier): this app's cPanel deploy
 * (.cpanel.yml) rsyncs everything except vendor/, so a new package would
 * silently never reach production without the admin manually running
 * `composer install` — which they don't have terminal access to do.
 */
class PlainTextSanitizer
{
    public static function clean(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        // Deliberately does NOT decode HTML entities afterward — an
        // HTML-encoded "&lt;script&gt;" left as literal text is inert
        // (Blade's {{ }} escapes it again on output either way), whereas
        // decoding it back could reintroduce real "<script>" characters.
        return trim(strip_tags($value));
    }
}
