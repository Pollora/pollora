<?php

declare(strict_types=1);

/**
 * HTML wrapper helpers.
 *
 * Global scope is intentional — these functions are passed as string callbacks
 * to WordPress hooks (e.g. Action::add('hook', 'wrapper_close')).
 *
 * @package Theme\Apiary
 */

if (! function_exists('wrapper_open')) {
    /**
     * Output or return an opening HTML tag with optional CSS classes.
     *
     * @param  string  $classes  Space-separated CSS class names.
     * @param  string  $tag      HTML element name (default: "div").
     * @param  bool    $echo     Whether to echo (true) or return (false) the markup.
     * @return string|void The opening tag when `$echo` is false.
     */
    function wrapper_open(string $classes = '', string $tag = 'div', bool $echo = true): void
    {
        $html = "<{$tag} class=\"{$classes}\">";
        if ($echo) {
            echo $html;
        }
    }
}

if (! function_exists('wrapper_close')) {
    /**
     * Output or return a closing HTML tag.
     *
     * @param  string  $tag   HTML element name (default: "div").
     * @param  bool    $echo  Whether to echo (true) or return (false) the markup.
     * @return string|void The closing tag when `$echo` is false.
     */
    function wrapper_close(string $tag = 'div', bool $echo = true): void
    {
        $tag = $tag === '' ? 'div' : $tag;
        $html = "</{$tag}>";
        if ($echo) {
            echo $html;
        }
    }
}