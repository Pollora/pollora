<?php

declare(strict_types=1);

/**
 * The screen people log in through.
 *
 * Colours, typography and radii are deliberately absent: they are already this
 * theme's truth in theme.json, and Pollora reads them from there. Restyle the
 * theme and the login screen follows, with nothing to keep in sync.
 *
 * What is here is what a design file has no business holding — which file the
 * logo is, where it links, what it says — and the two switches a project
 * flips. Delete this file and WordPress's own screen comes back untouched.
 */
return [
    'enabled' => true,

    'logo' => [
        /*
         * A path from the theme's root — not an asset URL. The file is read
         * from disk and inlined, so it needs no Vite entry and cannot 404.
         */
        'source' => 'resources/assets/images/pollora-logo.svg',

        /*
         * Height is left out on purpose: it is derived from the file's own
         * viewBox, so the wordmark keeps its proportions instead of being
         * squeezed into WordPress's 84×84 box.
         */
        'width' => 220,

        'url' => home_url('/'),
        'text' => get_bloginfo('name', 'display'),
    ],

    /*
     * The discreet "powered by" line in the footer. Turn it off and the
     * screen says nothing about what built it.
     */
    'powered_by' => true,
];
