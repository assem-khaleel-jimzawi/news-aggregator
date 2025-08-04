<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\NewsAggregatorService;
use App\Services\Providers\NewsApiService;
use App\Services\Providers\GuardianService;
use App\Services\Providers\NytService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register news providers
        $this->app->singleton(NewsApiService::class);
        $this->app->singleton(GuardianService::class);
        $this->app->singleton(NytService::class);

        // Register NewsAggregatorService with all providers
        $this->app->singleton(NewsAggregatorService::class, function ($app) {
            return new NewsAggregatorService([
                $app->make(NewsApiService::class),
                $app->make(GuardianService::class),
                $app->make(NytService::class),
            ]);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
