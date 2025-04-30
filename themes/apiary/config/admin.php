<?php

return [
    // default admin color scheme
    'color_scheme' => 'midnight',
    // WP grid build
    'wpgb' => [
        // enable only the facets menu and relocate it to WooCommerce Product menu
        'woocommerce_only' => true,
    ],
    'login' => [
        'logo' => [
            'max_height' => 128,
        ],
    ],
    // menus and submenus to disable
    'disabled_menus' => [
        'gutenberg',
        'limit-login-attempts',
        'wpgb' => [
            'wpgb-grids',
            'wpgb-cards',
            'wpgb-add-ons',
            'wpgb-settings',
            'wpgb-card-builder',
            'wpgb-grid-settings',
            'wpgb-dashboard',
        ],
    ],
];
