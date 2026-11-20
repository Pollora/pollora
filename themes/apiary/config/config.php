<?php

declare(strict_types=1);

return [
    'assets_path' => 'resources/assets',
    /*
    |--------------------------------------------------------------------------
    | Theme Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'asset_build' => [
        // directory target for assets generated
        'iconsDir' => 'public',
        // logo source for generate icons
        'source' => './resources/assets/media/icon.svg',
        'manifest' => [

            'appName' => 'Apiary',
            'appShortName' => 'Apiary',
            'appDescription' => 'WooCommerce theme built with Pollora, Tailwind CSS and Alpine.js',
            'background' => '#fff',
            'theme_color' => 'rgb(190, 24, 93)',
            'lang' => 'fr-FR',
        ],
    ],
];
