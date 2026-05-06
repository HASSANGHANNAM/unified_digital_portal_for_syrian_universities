<?php

namespace App\Services\Traits;

use Closure;
use Illuminate\Support\Facades\Cache;

trait HasCache
{
    protected function cacheRemember(string $key, int|\DateInterval|\DateTimeInterface|null $ttl, Closure $callback): mixed
    {
        return Cache::remember($key, $ttl, $callback);
    }

    protected function cacheForget(string $key): bool
    {
        return Cache::forget($key);
    }
}
