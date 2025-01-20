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
            ->container('theme')
            ->toFrontend()
            ->useVite();
        //Asset::add('font-inter', 'https://rsms.me/inter/inter.css', [], $this->theme->getHeader('version'))->to('front');

        Filter::add('woocommerce_enqueue_styles', function ($enqueueStyles) {
            unset($enqueueStyles['woocommerce-general']);		// Remove the default WooCommerce styles
            unset($enqueueStyles['woocommerce-layout']);		// Remove the layout
            unset($enqueueStyles['woocommerce-smallscreen']);	// Remove the smallscreen optimisation

            return $enqueueStyles;
        });
    }
}
