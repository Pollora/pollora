<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Active Theme
    |--------------------------------------------------------------------------
    |
    | It will assign the default active theme to be used if one is not set during
    | runtime.
    */
    'active' => null,

    /*
    |--------------------------------------------------------------------------
    | Parent Theme
    |--------------------------------------------------------------------------
    |
    | This is a parent theme for the theme specified in the active config
    | option. It works like the WordPress style theme hierarchy, if the blade
    | file is not found in the currently active theme, then it will look for it
    | in the parent theme.
    */
    'parent' => null,

    /*
    |--------------------------------------------------------------------------
    | Base Path
    |--------------------------------------------------------------------------
    |
    | The base path where all the themes are located.
    */
    'base_path' => base_path('themes'),

    'supports' => [
        'core-block-patterns',
        /* ----------------------------------------------------------------------------------------------- */
        // Post Thumbnails
        // @see https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
        /* ----------------------------------------------------------------------------------------------- */
        'post-thumbnails' => ['post', 'page'],

        /* ----------------------------------------------------------------------------------------------- */
        // Post Formats
        // @see https://developer.wordpress.org/themes/functionality/post-formats/
        /* ----------------------------------------------------------------------------------------------- */
        'post-formats' => [],

        /* ----------------------------------------------------------------------------------------------- */
        // Title Tag
        /* ----------------------------------------------------------------------------------------------- */
        'title-tag',

        'editor-styles',

        /* ----------------------------------------------------------------------------------------------- */
        // HTML 5
        /* ----------------------------------------------------------------------------------------------- */
        'html5' => ['comment-list', 'comment-form', 'search-form', 'gallery', 'caption'],

        /* ----------------------------------------------------------------------------------------------- */
        // Custom logo
        // @see https://developer.wordpress.org/themes/functionality/custom-logo/
        /* ----------------------------------------------------------------------------------------------- */
        'custom-logo' => [
            'height' => 250,
            'width' => 250,
            'flex-width' => true,
            'flex-height' => true,
        ],

        /* ----------------------------------------------------------------------------------------------- */
        // Feed Links
        /* ----------------------------------------------------------------------------------------------- */
        'automatic-feed-links',

        /* ----------------------------------------------------------------------------------------------- */
        // Customize Selective Refresh For Widgets
        /* ----------------------------------------------------------------------------------------------- */
        'customize-selective-refresh-widgets',

        /* ----------------------------------------------------------------------------------------------- */
        // Gutenberg
        /* ----------------------------------------------------------------------------------------------- */
        'wp-block-styles',
        'align-wide',
        'responsive-embeds',

        /* ----------------------------------------------------------------------------------------------- */
        // Starter Content
        // @see https://make.wordpress.org/core/2016/11/30/starter-content-for-themes-in-4-7/
        /* ----------------------------------------------------------------------------------------------- */
        //'starter-content' => [],

        /* ----------------------------------------------------------------------------------------------- */
        // Custom Background
        /* ----------------------------------------------------------------------------------------------- */
        'custom-background' => [
            'default-color' => 'ffffff',
            'default-image' => '',
            //    'wp-head-callback' => '_custom_background_cb',
            //    'admin-head-callback' => '',
            //    'admin-preview-callback' => ''
        ],
    ],
    'menus' => [
        'primary_menu' => 'Primary Menu',
    ],
    'sidebars' => [
        [
            'name' => 'Main Sidebar',
            'id' => 'sidebar-1',
            'description' => 'Widgets in this area will be shown on all posts and pages.',
            'before_widget' => '<li id="%1$s" class="widget %2$s">',
            'after_widget' => '</li>',
            'before_title' => '<h2 class="widgettitle">',
            'after_title' => '</h2>',
        ],
    ],
];
