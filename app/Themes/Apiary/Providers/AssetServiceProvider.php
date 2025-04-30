<?php

namespace App\Themes\Apiary\Providers;

use Illuminate\Support\ServiceProvider;
use Pollora\Support\Facades\Asset;
use Pollora\Support\Facades\Filter;

class AssetServiceProvider extends ServiceProvider
{
    protected $theme;

    /**
     * Theme Assets
     */
    public function register()
    {
    }

    public function boot()
    {
        Asset::add('default/script', 'assets/app.js')
            ->toFrontend()
            ->useVite();

        Filter::add('woocommerce_enqueue_styles', function (array $enqueueStyles) {
            // Remove the default WooCommerce styles, layout ands the smallscreen optimisation
            unset($enqueueStyles['woocommerce-general'], $enqueueStyles['woocommerce-layout'], $enqueueStyles['woocommerce-smallscreen']);
            return $enqueueStyles;
        });
    }
}
