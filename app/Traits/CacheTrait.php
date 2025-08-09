<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

trait CacheTrait
{

    /**
     * Cache a value with or without tags for 1 hour by default
     * @param string $key
     * @param $callback
     * @param $tags
     * @param int $ttl
     * @return mixed
     */
    protected function cacheRemember(string $key, $callback, $tags = null, int $ttl = 3600): mixed
    {
        if ($tags) {
            return Cache::tags($tags)->remember($key, $ttl, $callback);
        } else {
            return Cache::remember($key, $ttl, $callback);
        }
    }

    protected function cacheRememberForever(string $key, $callback, $tags = null): mixed
    {
        if ($tags) {
            return Cache::tags($tags)->rememberForever($key, $callback);
        } else {
            return Cache::rememberForever($key, $callback);
        }
    }

    /**
     * Delete a value from cache with tags or without
     * @param string|null $key
     * @param null $tags
     * @return void
     */
    protected function cacheForget(?string $key = null, $tags = null): void
    {
        if ($tags) {
            Cache::tags($tags)->flush();
        } else {
            Cache::forget($key);
        }
    }

    /**
     * Delete all values from cache with tags or without
     * @param string $key
     * @return void
     */
    protected function searchAndDelete(string $key): void
    {
        $keys = Redis::keys($key);
        if (!empty($keys)) {
            Redis::del($keys);
        }
    }

    /**
     * Get a value from cache with tags or without
     * @param string $key
     * @param $tags
     * @return mixed
     */
    protected function cacheGet(string $key, $tags = null): mixed
    {
        if ($tags) {
            return Cache::tags($tags)->get($key);
        } else {
            return Cache::get($key);
        }
    }

    /**
     * Save a primitive value to cache with tags or without
     * @param string $key
     * @param $value
     * @param null $tags
     * @param null $ttl
     * @return void
     */
    protected function cacheSet(string $key, $value, $tags = null, $ttl = null): void
    {
        if ($tags) {
            Cache::tags($tags)->put($key, $value, $ttl);
        } else {
            Cache::set($key, $value, $ttl);
        }
    }

    /**
     * Check if a value exists in cache with tags or without
     * @param string|null $key
     * @param $tags
     * @return bool
     */
    protected function cacheHas(?string $key = null, $tags = null): bool
    {
        if ($tags) {
            return Cache::tags($tags)->has($key);
        } else {
            return Cache::has($key);
        }
    }
}
