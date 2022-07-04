<?php

namespace Chrisabey84\LaravelCachedOptionsList;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

trait HasCachedOptionsList
{
    public static function bootHasCachedOptionsList(): void
    {
        self::saving(fn () => self::clearOptionsCache());
        self::deleted(fn () => self::clearOptionsCache());
    }

    public static function asSelectArray(): array
    {
        return Cache::rememberForever(self::getCacheKey(), function () {
            return self::buildQuery()
                ->pluck(config('cached-options-list.value'), config('cached-options-list.key'))
                ->toArray();
        });
    }

    public static function clearOptionsCache(): void
    {
        Cache::forget(self::getCacheKey());
    }

    protected static function buildQuery(): Builder
    {
        /* @var Model static */
        return static::query();
    }

    protected static function getCacheKey(): string
    {
        return Str::of(static::class)
            ->classBasename()
            ->kebab()
            ->append('-cached-options-list')
            ->toString();
    }
}
