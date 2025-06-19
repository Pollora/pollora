<?php

declare(strict_types=1);

namespace Theme\Default\Providers;

use Illuminate\Support\ServiceProvider;
use Pollora\Support\Facades\Asset;

class AssetServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Asset::add('default/script', 'app.js')
            ->container('theme')
            ->toFrontend()
            ->useVite();
    }
}
