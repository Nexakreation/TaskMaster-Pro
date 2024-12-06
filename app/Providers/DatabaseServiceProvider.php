<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Connectors\ConnectionFactory;
use PDO;

class DatabaseServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('db', function ($app) {
            $factory = new ConnectionFactory($app);

            $dbConfig = [
                'driver' => 'mysql',
                'host' => 'localhost',
                'database' => 'todo_app',
                'username' => 'root',
                'password' => '',
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
            ];

            return new DatabaseManager($app, $factory, ['default' => $dbConfig]);
        });
    }

    public function boot()
    {
        //
    }
} 