<?php

declare(strict_types=1);

namespace Theme\Apiary\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class ThemeComponentProvider extends ServiceProvider
{
    public function boot()
    {
        $this->bootBladeComponents();
    }

    protected function bootBladeComponents(): self
    {
        if (version_compare($this->app->version(), '8.0.0', '>=')) {
            Blade::componentNamespace('Theme\Apiary\\View\\Components', 'theme');
        }

        return $this;
    }
}
