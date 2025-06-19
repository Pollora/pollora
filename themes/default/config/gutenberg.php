<?php

declare(strict_types=1);

/**
 * Edit this file in order to configure the Gutenberg editor
 */
return [
    'categories' => [
        /*
        |--------------------------------------------------------------------------
        | Register block categories
        |--------------------------------------------------------------------------
        |
        | The key of the array of arguments is used as a slug for the category
        | See : https://developer.wordpress.org/block-editor/reference-guides/filters/block-filters/#block_categories_all
        | Note : label is accepted for a "title" replacement.
        |
        */
        'blocks' => [
            'theme/sample' => [
                'label' => 'Sample',
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Register pattern categories
        |--------------------------------------------------------------------------
        |
        | The key of the array of arguments is used as a slug for the category
        | See : https://developer.wordpress.org/block-editor/reference-guides/block-api/block-patterns/#block-pattern-categories
        |
        */
        'patterns' => [
            'theme/patterns' => [
                'label' => 'Theme',
            ],
        ],
    ],
];
