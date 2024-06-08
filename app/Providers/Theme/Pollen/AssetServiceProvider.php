<?php

declare(strict_types=1);

namespace App\Providers\Theme\Pollen;

use Illuminate\Support\ServiceProvider;
use Pollen\Support\Facades\Action;
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
        Asset::add('pollen/app-style', Theme::path('css/app.css'))
            ->toFrontend()
            ->useVite();
        Asset::add('pollen/app-js', Theme::path('js/app.js'))
            ->toFrontend()
            ->useVite();
    }
}
