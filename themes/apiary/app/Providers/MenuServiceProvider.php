<?php

declare(strict_types=1);

namespace Theme\Apiary\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
