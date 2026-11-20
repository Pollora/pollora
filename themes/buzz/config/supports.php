<?php

declare(strict_types=1);

/**
 * Edit this file in order to add theme support features.
 *
 * @see https://developer.wordpress.org/reference/functions/add_theme_support/
 *
 * A full site editing theme leaves out what only a classic theme needs: no
 * `custom-logo` (the masthead pattern sets the site title in type, not an
 * image), no `customize-selective-refresh-widgets` (there is no widget
 * area). `block-templates` is not a switch to flip — WordPress detects it on
 * its own from `templates/index.html`.
 */
return [
    /* ----------------------------------------------------------------------------------------------- */
    // Post Thumbnails
    // @see https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
    /* ----------------------------------------------------------------------------------------------- */
    'post-thumbnails' => ['post', 'page'],

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
    // Feed Links
    /* ----------------------------------------------------------------------------------------------- */
    'automatic-feed-links',

    /* ----------------------------------------------------------------------------------------------- */
    // Gutenberg
    /* ----------------------------------------------------------------------------------------------- */
    'wp-block-styles',
    'align-wide',
    'responsive-embeds',
];
