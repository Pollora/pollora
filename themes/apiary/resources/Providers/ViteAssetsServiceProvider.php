<?php

namespace Theme\Providers;

use Illuminate\Support\ServiceProvider;
use Theme\Core\ViteJs\AssetLoader;

class ViteAssetsServiceProvider extends ServiceProvider
{
    /**
     * Theme Assets via ViteJS
     */
    public function register()
    {
        $this->app->bind(AssetLoader::class, function ($app) {
            return new AssetLoader();
        });
    }
}
