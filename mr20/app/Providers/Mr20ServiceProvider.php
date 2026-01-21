<?php

namespace App\Mr20\Providers;

use App\Mr20\Services\Mr20Client;
use Illuminate\Support\ServiceProvider;

class Mr20ServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind MR20 HTTP client (expects configuration in services.mr20)
        $this->app->singleton(Mr20Client::class, function () {
            $baseUrl = config('services.mr20.base_url');
            $apiKey = config('services.mr20.api_key');

            return new Mr20Client($baseUrl, $apiKey);
        });
    }

    public function boot(): void
    {
        // Load MR20 routes
        if (file_exists(__DIR__ . '/../../routes/mr20.php')) {
            $this->loadRoutesFrom(__DIR__ . '/../../routes/mr20.php');
        }

        // Load migrations if this module is included in a full Laravel app
        if (is_dir(__DIR__ . '/../../database/migrations')) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        }
    }
}


