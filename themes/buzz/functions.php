<?php

declare(strict_types=1);

use Pollora\Modules\Domain\Enums\ModuleType;
use Pollora\Theme\Domain\Contracts\ThemeRegistrarInterface;

/*
 * Register the theme with whichever Pollora version is running.
 *
 * `make:theme` downloads this theme's latest tag regardless of the framework
 * the site has installed, so this file is the one place that has to work on
 * both: `pollora_register()` arrived in 13.32, while sites still on 13.4 only
 * have the registrar contract. Calling the helper unconditionally takes those
 * sites down with a fatal before anything can report why — so each path is
 * guarded, and an unknown combination leaves the theme unregistered rather
 * than fatal.
 */

if (function_exists('pollora_register')) {
    pollora_register(ModuleType::Theme);

    return;
}

if (! function_exists('app') || ! app()->bound(ThemeRegistrarInterface::class)) {
    return;
}

try {
    app(ThemeRegistrarInterface::class)->register();
} catch (Throwable $e) {
    if (app()->bound('log')) {
        app('log')->error('Unable to register the theme: '.$e->getMessage());
    }
}
