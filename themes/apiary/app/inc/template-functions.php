<?php

declare(strict_types=1);

/**
 * Theme-specific template functions and WordPress hook callbacks.
 *
 * - Adds contextual body classes (hfeed, no-sidebar).
 * - Registers ACF location rules for menu-level field groups.
 * - Outputs pingback discovery link on singular pages.
 * - Sets the global content width for oEmbed and image sizing.
 *
 * Global scope is intentional — hooks and filters registered here rely on
 * WordPress/Pollora facades at file load time.
 *
 * @package Theme\Apiary
 */

use Pollora\Support\Facades\Action;
use Pollora\Support\Facades\Filter;

/**
 * Add contextual classes to the `<body>` element.
 *
 * @param  string[]  $classes  Existing body classes.
 * @return string[] Modified class list.
 */
Filter::add('body_class', function (array $classes): array {
    if (! is_singular()) {
        $classes[] = 'hfeed';
    }

    if (! is_active_sidebar('sidebar-1')) {
        $classes[] = 'no-sidebar';
    }

    return $classes;
});

/**
 * Register an ACF location rule type for targeting specific menu depth levels.
 *
 * Allows field groups to be shown only on menu items at a given depth
 * (e.g. "1er niveau" = top-level items only).
 *
 * @param  array<string, array<string, string>>  $choices  Existing rule types.
 * @return array<string, array<string, string>> Modified rule types.
 */
add_filter('acf/location/rule_types', function (array $choices): array {
    $choices['Menu']['menu_level'] = 'Niveau de menu';

    return $choices;
});

/**
 * Provide selectable values for the menu_level ACF location rule.
 *
 * @param  array<int, string>  $choices  Existing values.
 * @return array<int, string> Menu depth labels (0-indexed).
 */
add_filter('acf/location/rule_values/menu_level', function (array $choices): array {
    $choices[0] = '1er niveau';
    $choices[1] = '2nd niveau';
    $choices[2] = '3ème niveau';
    $choices[3] = '4ème niveau';

    return $choices;
});

/**
 * Evaluate whether a menu item matches the menu_level ACF location rule.
 *
 * @param  bool                 $match    Current match state.
 * @param  array{operator: string, value: string}  $rule     The rule being evaluated.
 * @param  array{nav_menu_item_depth?: int}         $options  Current screen context.
 * @return bool Whether the rule matches.
 */
add_filter('acf/location/rule_match/menu_level', function (bool $match, array $rule, array $options): bool {
    if (! function_exists('get_current_screen')) {
        return $match;
    }

    $current_screen = get_current_screen();
    if (! $current_screen || $current_screen->base !== 'nav-menus') {
        return $match;
    }

    if ($rule['operator'] === '==') {
        $match = isset($options['nav_menu_item_depth']) && (string) $options['nav_menu_item_depth'] === $rule['value'];
    }

    return $match;
}, 10, 3);

/**
 * Output a `<link rel="pingback">` tag on singular pages with pings open.
 */
Action::add('wp_head', function (): void {
    if (is_singular() && pings_open()) {
        echo '<link rel="pingback" href="' . esc_url(get_bloginfo('pingback_url')) . '">';
    }
});

/**
 * Set the global content width (used by oEmbed and image sizing).
 *
 * @global int $content_width
 */
Action::add('after_setup_theme', function (): void {
    $GLOBALS['content_width'] = 640;
}, 0);