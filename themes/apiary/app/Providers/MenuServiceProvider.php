<?php

declare(strict_types=1);

namespace Theme\Apiary\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

/**
 * Shares a `$menus` variable with all Blade views.
 *
 * Reads menu locations from `config/menus.php` and exposes them as
 * a keyed array of empty collections. Individual views or composers
 * can populate these collections with actual menu items as needed.
 *
 * @see config('theme.menus')
 */
class MenuServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (! $view->offsetExists('menus')) {
                $view->with('menus', $this->buildMenus());
            }
        });
    }

    /**
     * Build empty collection stubs for each registered menu location.
     *
     * @return array<string, \Illuminate\Support\Collection>
     */
    private function buildMenus(): array
    {
        $menusConfig = config('theme.menus', []);
        $menus = [];

        foreach (array_keys($menusConfig) as $menuKey) {
            $menus[$menuKey] = collect();
        }

        return $menus;
    }
}
