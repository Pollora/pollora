<?php

/**
 * Edit this file in order to add WordPress sidebars to your theme.
 *
 * @see https://developer.wordpress.org/reference/functions/register_sidebar/
 */
return [
    [
        'name' => __('Shop sidebar', 'apiary'),
        'id' => 'shop-sidebar',
        'description' => __('Area of shop sidebar', 'apiary'),
        'class' => 'custom',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<h2>',
        'after_title' => '</h2>',
    ],
];
