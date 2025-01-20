<?php

namespace Theme\Providers;

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
            Blade::componentNamespace('Theme\\View\\Components', 'theme');
        }

        return $this;
    }
}
