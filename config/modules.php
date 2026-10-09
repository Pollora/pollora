<?php

declare(strict_types=1);

use Nwidart\Modules\Activators\FileActivator;
use Pollora\Modules\Infrastructure\Activation\ConnectorActivator;
use Pollora\Modules\Infrastructure\Activation\ModuleConnectors;

/*
|--------------------------------------------------------------------------
| Laravel modules
|--------------------------------------------------------------------------
|
| Published with `php artisan vendor:publish --tag=pollora-modules`. The keys
| below are merged over nwidart/laravel-modules' own configuration, and read
| while it registers — before any service provider of the application — so
| they only apply from this file.
|
*/

return [
    // Delegates nwidart's enabled state to the connector below
    'activator' => 'pollora',
    'activators' => [
        'pollora' => ['class' => ConnectorActivator::class],
        'file' => [
            'class' => FileActivator::class,
            'statuses-file' => base_path('modules_statuses.json'),
        ],
    ],

    /*
    | Where the enabled state lives: "json" (modules_statuses.json, nwidart's
    | file), "database" (the pollora_modules WordPress option, survives a
    | deployment), "config" (shipped with the code, read-only), or a connector
    | of your own: ['class' => App\Modules\RedisConnector::class].
    */
    'connector' => env('MODULES_CONNECTOR', 'json'),
    'connectors' => [
        'json' => ['path' => base_path('modules_statuses.json')],
        'database' => ['option' => 'pollora_modules', 'fallback' => 'json'],
        'config' => [
            'states' => [],
            'enabled' => ModuleConnectors::names(env('MODULES_ENABLED', '')),
            'disabled' => ModuleConnectors::names(env('MODULES_DISABLED', '')),
        ],
    ],

    // Forced states, applied over any connector; their toggle is disabled in the admin
    'locked' => [
        'enabled' => ModuleConnectors::names(env('MODULES_LOCKED_ENABLED', '')),
        'disabled' => ModuleConnectors::names(env('MODULES_LOCKED_DISABLED', '')),
    ],

    // The Plugins › Modules view: switches, and who may use them
    'admin' => [
        'toggle' => env('MODULES_ADMIN_TOGGLE', true),
        'capability' => 'activate_plugins',
    ],
];
