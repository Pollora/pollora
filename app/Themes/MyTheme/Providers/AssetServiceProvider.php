<?php

declare(strict_types=1);

namespace App\Themes\MyTheme\Providers;

use Illuminate\Support\ServiceProvider;
use Pollora\Support\Facades\Asset;
use Pollora\Support\Facades\Theme;

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
        Asset::add('my-theme/script', 'asset/app.js')
            ->container('theme')
            ->toFrontend()
            ->useVite();
    }
}
