<?php

use Pollora\Support\Facades\Action;
use Pollora\Support\Facades\Filter;

/**
 * Adds custom classes to the array of body classes.
 *
 * @param  array  $classes Classes for the body element.
 * @return array
 */
Filter::add('body_class', function ($classes) {
    // Adds a class of hfeed to non-singular pages.
    if (! is_singular()) {
        $classes[] = 'hfeed';
    }

    // Adds a class of no-sidebar when there is no sidebar present.
    if (! is_active_sidebar('sidebar-1')) {
        $classes[] = 'no-sidebar';
    }

    return $classes;
});

add_filter('acf/location/rule_types', function ($choices): array {
    $choices['Menu']['menu_level'] = 'Niveau de menu';

    return $choices;
});

add_filter('acf/location/rule_values/menu_level', function ($choices): array {
    $choices[0] = '1er niveau';
    $choices[1] = '2nd niveau';
    $choices[2] = '3ème niveau';
    $choices[3] = '4ème niveau';

    return $choices;
});

add_filter('acf/location/rule_match/menu_level', function ($match, $rule, $options): bool {
    if (! function_exists('get_current_screen')) {
        return $match;
    }

    $current_screen = get_current_screen();
    if (! $current_screen || $current_screen->base !== 'nav-menus') {
        return $match;
    }

    if ($rule['operator'] == '==') {
        $match = isset($options['nav_menu_item_depth']) && (string) $options['nav_menu_item_depth'] === $rule['value'];
    }

    return $match;
}, 10, 3);

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
Action::add('wp_head', function () {
    if (is_singular() && pings_open()) {
        echo '<link rel="pingback" href="'.esc_url(get_bloginfo('pingback_url')).'">';
    }
});

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 */
Action::add('after_setup_theme', function () {
    $GLOBALS['content_width'] = 640;
}, 0);
