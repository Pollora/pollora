<?php

declare(strict_types=1);

namespace Theme\Apiary\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

/**
 * Registers the theme's Blade component namespace.
 *
 * Maps `<x-theme::*>` components to the `Theme\Apiary\View\Components` namespace,
 * allowing Blade views to reference theme components with a short prefix.
 *
 * Example: `<x-theme::button />` resolves to `Theme\Apiary\View\Components\Button`.
 */
class ThemeComponentProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->bootBladeComponents();
    }

    /**
     * Register the `theme` Blade component namespace.
     */
    protected function bootBladeComponents(): self
    {
        if (version_compare($this->app->version(), '8.0.0', '>=')) {
            Blade::componentNamespace('Theme\Apiary\\View\\Components', 'theme');
        }

        return $this;
    }
}
