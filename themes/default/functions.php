<?php

declare(strict_types=1);

/**
 * SolidarMonde Theme Functions
 *
 * This file registers the theme with the Pollora framework using the
 * ThemeRegistrar service directly.
 */

use Pollora\Theme\Domain\Contracts\ThemeRegistrarInterface;

// Ensure Pollora framework is loaded
if (! function_exists('app') || ! app()->bound(ThemeRegistrarInterface::class)) {
    return;
}

// Register this theme as the active theme using the registrar service directly
try {
    /** @var ThemeRegistrarInterface $themeRegistrar */
    $themeRegistrar = app(ThemeRegistrarInterface::class);

    // Register the theme with its path (auto-detected) and metadata
    $themeRegistrar->register();

} catch (Exception $e) {
    // TODO Make a Pollora Logger
    $logger = app()->bound('log') ? app('log') : null;

    if ($logger) {
        $logger->error('Failed to register SolidarMonde theme', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
    }
}
