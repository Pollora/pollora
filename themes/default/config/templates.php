<?php

declare(strict_types=1);

/**
 * Modify this file to set up your theme's template configurations.
 *
 * You can specify merely a template identifier by only setting a key.
 * To enhance the user experience, you can also define a display title as its
 * value. Additionally, as a second argument, you can list the post types
 * where your template will be accessible.
 */
return [
    'home' => [
        'label' => 'Homepage',
        'post_types' => ['page'],
    ],
];
