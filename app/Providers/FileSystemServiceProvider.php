<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\Filesystem;

class FileSystemServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('files', function () {
            return new Filesystem;
        });
    }

    public function boot()
    {
        //
    }
} 