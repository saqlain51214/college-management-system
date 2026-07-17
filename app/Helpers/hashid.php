<?php

if (! function_exists('hid')) {
    function hid(int $id): string
    {
        return app(\App\Services\HashidService::class)->encode($id);
    }
}

if (! function_exists('hid_decode')) {
    function hid_decode(string $hash): ?int
    {
        return app(\App\Services\HashidService::class)->decode($hash);
    }
}
