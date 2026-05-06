<?php

declare(strict_types=1);

/**
 * Edit this file in order to add WordPress sidebars to your theme.
 *
 * @see https://developer.wordpress.org/reference/functions/register_sidebar/
 */
return [
    [
        'name' => __('Formulaire footer', 'solidarmonde'),
        'id' => 'footer-widget',
        'class' => 'custom',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<h2>',
        'after_title' => '</h2>',
    ],
    [
        'name' => __('Formulaire de contact', 'solidarmonde'),
        'id' => 'contact-widget',
        'class' => 'custom',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<h2>',
        'after_title' => '</h2>',
    ],
];
