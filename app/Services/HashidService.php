<?php

namespace App\Services;

use Hashids\Hashids;

class HashidService
{
    private Hashids $hashids;

    public function __construct()
    {
        $this->hashids = new Hashids(
            config('app.key'),  // salt from APP_KEY
            8,                  // min length
            'abcdefghijklmnopqrstuvwxyz1234567890'
        );
    }

    public function encode(int $id): string
    {
        return $this->hashids->encode($id);
    }

    public function decode(string $hash): ?int
    {
        $decoded = $this->hashids->decode($hash);
        return $decoded[0] ?? null;
    }
}
