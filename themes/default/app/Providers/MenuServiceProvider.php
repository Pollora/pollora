<?php

declare(strict_types=1);

namespace Theme\Default\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::share('menu', (function () {
            try {
                return menu('primary_menu');
            } catch (\Exception $e) {
                return [];
            }
        })());
    }
}
