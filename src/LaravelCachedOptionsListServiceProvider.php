<?php

namespace Chrisabey84\LaravelCachedOptionsList;

use Chrisabey84\LaravelCachedOptionsList\Commands\ClearCache;
use Illuminate\Support\ServiceProvider;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelCachedOptionsListServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/cached-options-list.php' => config_path('cached-options-list.php'),
        ]);

        $this->mergeConfigFrom(
            __DIR__ . '/../config/cached-options-list.php',
            'cached-options-list'
        );

        if ($this->app->runningInConsole()) {
            $this->commands([
                ClearCache::class,
            ]);
        }
    }
}
