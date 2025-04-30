<?php

namespace Theme\Providers;

use Illuminate\Support\ServiceProvider;
use Theme\Core\ViteJs\AssetLoader;
use Pollora\Core\ThemeManager;
use Pollora\Support\Facades\Asset;
use Pollora\Support\Facades\Filter;

class AssetServiceProvider extends ServiceProvider
{
    protected $theme;

    /**
     * Theme Assets
     *
     * Here we define the loaded assets from our previously defined
     * "dist" directory. Assets sources are located under the root "assets"
     * directory and are then compiled, thanks to Laravel Mix, to the "dist"
     * folder.
     *
     * @see https://laravel-mix.com/
     */
    public function register()
    {
        /** @var ThemeManager $theme */
        $this->theme = $this->app->make('wp.theme');
    }

    public function boot(AssetLoader $assetLoader)
    {
        $assetLoader->loadAssets('themosis/frontend', get_stylesheet_directory(), '8000', 'frontend');
        $assetLoader->loadAssets('themosis/editor', get_stylesheet_directory(), '4000', 'editor');

        Asset::add('font-inter', 'https://rsms.me/inter/inter.css', [], $this->theme->getHeader('version'))->to('front');

        Filter::add('woocommerce_enqueue_styles', function ($enqueueStyles) {
            unset($enqueueStyles['woocommerce-general']);		// Remove the default WooCommerce styles
            unset($enqueueStyles['woocommerce-layout']);		// Remove the layout
            unset($enqueueStyles['woocommerce-smallscreen']);	// Remove the smallscreen optimisation

            return $enqueueStyles;
        });
    }
}
