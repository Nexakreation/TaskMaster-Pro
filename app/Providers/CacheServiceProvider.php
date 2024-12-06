<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\CacheManager;
use Illuminate\Contracts\Cache\Factory;

class CacheServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('cache', function ($app) {
            return new CacheManager($app);
        });

        $this->app->singleton('cache.store', function ($app) {
            return $app['cache']->driver();
        });

        $this->app->singleton(Factory::class, function ($app) {
            return $app['cache'];
        });
    }
} 