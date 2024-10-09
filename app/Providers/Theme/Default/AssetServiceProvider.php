<?php

declare(strict_types=1);

namespace App\Providers\Theme\Default;

use Illuminate\Support\ServiceProvider;
use Pollen\Support\Facades\Asset;
use Pollen\Support\Facades\Theme;

class AssetServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Asset::add('default/assets', Theme::path('assets/app.js'))
            ->toFrontend()
            ->useVite();

    }
}
