<?php

/**
 * Edit this file in order to configure your theme templates.
 *
 * You can define just a template slug by only defining a key.
 * For a better user experience, you can define a display title as a
 * value and as a second argument, you can specify a list of post types
 * where your template is available.
 */
return [
    'single-product' => [
        'enabled_tabs' => [
            'description' => false,
            'reviews' => true,
            'additional_information' => true,
        ],
        'summary' => [
            'class' => 'flex justify-between',
        ],
        'quantity_input' => [
            'class' => 'rounded-r-none py-3 px-8',
        ],
        'sale_flash' => [
            'class' => 'absolute font-semibold text-white bg-indigo-600 top-2 right-2 px-2 py-1 rounded-md z-10',
        ],
    ],
    'archive-product' => [
        'product_grid' => [
            'class' => 'grid grid-cols-1 gap-y-4 sm:grid-cols-2 sm:gap-x-6 sm:gap-y-10 lg:gap-x-8 xl:grid-cols-3',
        ],
        'product_title' => [
            'class' => 'text-sm font-medium text-gray-900',
        ],
        'product_facets' => [
            'count' => [
                'class' => 'product-count',
            ],
            'sort' => [
                'class' => 'sort-product group inline-flex justify-center items-center text-sm font-medium text-gray-700',
            ],
            'pagination' => [
                'class' => 'text-center mt-4',
            ],
        ],
        'product_thumbnail' => [
            'class' => 'w-full h-full object-center object-cover sm:w-full sm:h-full',
        ],
    ],
    'cart' => [
        'product_thumbnail' => [
            'class' => 'w-24 h-24 rounded-md object-center object-cover sm:w-48 sm:h-48',
        ],
    ],
    'checkout' => [
        'fields' => [
            'billing_address_1' => [
                'class' => ['sm:col-span-2'],
            ],
            'billing_address_2' => [
                'class' => ['sm:col-span-2'],
            ],
            'shipping_address_1' => [
                'class' => ['sm:col-span-2'],
            ],
            'shipping_address_2' => [
                'class' => ['sm:col-span-2'],
            ],
        ],
        'product_thumbnail' => [
            'class' => 'w-20 h-20 rounded-md object-center object-cover',
        ],
    ],
    'thank_you' => [
        'product_thumbnail' => [
            'class' => 'flex-none w-20 h-20 object-center object-cover bg-gray-100 rounded-lg sm:w-40 sm:h-40',
        ],
    ],
    'related' => [
        'class' => 'mt-8 grid grid-cols-1 gap-y-12 sm:grid-cols-2 sm:gap-x-6 lg:grid-cols-4 xl:gap-x-8',
    ],
    'review' => [
        'form' => [
            'wrapper' => [
                'class' => 'mt-4 mb-6',
            ],
            'label' => [
                'class' => 'block text-sm font-medium text-gray-700 mb-1',
            ],
            'textarea' => [
                'class' => 'shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md',
            ],
            'input' => [
                'class' => 'mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md',
            ],
            'submit' => [
                'wrapper' => [
                    'class' => 'py-3 text-right',
                ],
                'class' => 'inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500',
            ],
            'cookie' => [
                'wrapper' => [
                    'class' => 'py-3 flex items-start',
                ],
                'label' => [
                    'class' => 'text-sm font-medium text-gray-700 mb-1 ml-3',
                ],
                'checkbox' => [
                    'class' => 'focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded',
                ],
            ],
        ],
    ],
];
