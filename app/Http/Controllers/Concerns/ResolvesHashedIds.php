<?php

namespace App\Http\Controllers\Concerns;

trait ResolvesHashedIds
{
    protected function decodeHid(string $hid): int
    {
        $id = hid_decode($hid);
        abort_if($id === null, 404);
        return $id;
    }
}
