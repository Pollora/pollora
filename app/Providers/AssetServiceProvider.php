<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Pollen\Support\Facades\Asset;

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
        Asset::add('app','resources/css/app.css')
            ->toFrontend()
            ->useVite();

        Asset::add('app','resources/js/app.js')
            ->toFrontend()
            ->useVite();
    }
}
