<?php

declare(strict_types=1);

namespace Module\BlocksDemo\Providers;

use Illuminate\Support\ServiceProvider;
use Pollora\Asset\Application\Services\AssetManager;

class AssetServiceProvider extends ServiceProvider
{
    public function boot(AssetManager $assetManager): void
    {
        $assetManager->addContainer('module.blocks-demo', [
            'hot_file' => public_path('blocks-demo.hot'),
            'build_directory' => 'build/module/blocks-demo',
            'manifest_path' => 'manifest.json',
            'base_path' => 'resources/assets/',
        ]);
    }
}
