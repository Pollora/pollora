<?php

declare(strict_types=1);

namespace Theme\Apiary\Providers;

use Illuminate\Support\ServiceProvider;
use Pollora\Support\Facades\Action;
use Pollora\Support\Facades\Asset;
use Pollora\Support\Facades\Filter;

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
        Asset::add('apiary/script', 'app.js')
            ->container('theme')
            ->toFrontend()
            ->useVite();

        // Remove default WooCommerce styles — the theme handles all styling via Tailwind
        Filter::add('woocommerce_enqueue_styles', function (array $enqueueStyles) {
            unset($enqueueStyles['woocommerce-general'], $enqueueStyles['woocommerce-layout'], $enqueueStyles['woocommerce-smallscreen']);
            return $enqueueStyles;
        });
    }
}
