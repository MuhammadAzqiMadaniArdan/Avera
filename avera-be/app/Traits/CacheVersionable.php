<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait CacheVersionable
{
    public function versionKey(string $key): int
    {
        return Cache::get("$key:version", 1);
    }

    public function invalidateCache(string $key): void
    {
        Cache::increment("$key:version");
    }
}
