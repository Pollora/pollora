<?php

declare(strict_types=1);

namespace Theme\PolloraStarter\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (! $view->offsetExists('menu')) {
                $view->with('menu', $this->getMenu('primary'));
            }
        });
    }

    /**
     * @return list<object>
     */
    private function getMenu(string $location): array
    {
        $locations = get_nav_menu_locations();

        if (! isset($locations[$location])) {
            return $this->getFallbackMenu();
        }

        $items = wp_get_nav_menu_items($locations[$location]);

        if (! $items) {
            return $this->getFallbackMenu();
        }

        return array_map(fn (\WP_Post $item) => (object) [
            'title' => $item->title,
            'url' => $item->url,
            'ID' => $item->ID,
            'classes' => $item->classes ?? [],
            'current' => $item->current ?? false,
        ], $items);
    }

    /**
     * @return list<object>
     */
    private function getFallbackMenu(): array
    {
        $items = [];

        $items[] = (object) [
            'title' => __('Home', 'pollora'),
            'url' => home_url('/'),
            'ID' => 0,
            'classes' => [],
            'current' => is_front_page(),
        ];

        $pages = get_pages(['sort_column' => 'menu_order,post_title', 'post_status' => 'publish']);

        foreach ($pages as $page) {
            $items[] = (object) [
                'title' => $page->post_title,
                'url' => get_permalink($page->ID),
                'ID' => $page->ID,
                'classes' => [],
                'current' => is_page($page->ID),
            ];
        }

        return $items;
    }
}
