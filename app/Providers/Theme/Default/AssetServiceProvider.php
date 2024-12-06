<?php

declare(strict_types=1);

namespace App\Providers\Theme\Default;

use Illuminate\Support\ServiceProvider;
use Pollora\Support\Facades\Asset;

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

        Asset::add('default/assets', 'assets/app.js')
            ->container('theme')
            ->toFrontend()
            ->useVite();

        Asset::add('test-js', 'resources/js/app.js')->container('app')->toFrontend()->useVite();
    }
}
