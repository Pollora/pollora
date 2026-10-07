<?php

declare(strict_types=1);

/**
 * The screen people log in through.
 *
 * No colour, no typeface, no radius here on purpose: this theme already
 * declares all of that in theme.json, and Pollora reads it from there. The
 * login screen follows the theme, and there is nothing to keep in sync.
 *
 * What is left is what theme.json has no business holding — which file the
 * logo is, where it links, what it says — and two switches.
 */
return [
    'enabled' => true,

    'logo' => [
        /*
         * A path from the theme's root, not an asset URL: the file is read
         * from disk and inlined, so it needs no Vite entry and cannot 404.
         * Swap it for the site's own mark; the Pollora logo is here as the
         * example of what this setting is for.
         */
        'source' => 'resources/assets/images/pollora-logo.svg',

        /*
         * Height is derived from the file's own viewBox when left out, so a
         * wordmark is not squeezed into WordPress's 84×84 box.
         */
        'width' => 220,

        'url' => home_url('/'),
        'text' => get_bloginfo('name', 'display'),
    ],

    'powered_by' => true,
];
