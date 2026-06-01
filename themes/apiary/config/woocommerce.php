<?php

declare(strict_types=1);

/**
 * Edit this file in order to configure your theme templates.
 *
 * You can define just a template slug by only defining a key.
 * For a better user experience, you can define a display title as a
 * value and as a second argument, you can specify a list of post types
 * where your template is available.
 */
return [
    'search' => [
        'suggestions' => true,
        'min_chars'   => 3,
        'debounce'    => 300, // ms
        'max_results' => 6,
    ],
    'single-product' => [
        // Show a centered modal instead of toast + mini-cart after add-to-cart
        'add_to_cart_confirmation' => [
            'enabled' => true,
        ],
        'sticky_add_to_cart' => [
            'enabled' => true,
            'mobile' => true,    // Always visible fixed bottom on mobile
            'desktop' => true,   // Shows when original button leaves viewport
        ],
        'enabled_tabs' => [
            'description' => true,
            'reviews' => true,
            'additional_information' => true,
        ],
        'summary' => [
            'class' => 'flex flex-wrap justify-between items-baseline gap-x-6 gap-y-1',
        ],
        'quantity_input' => [
            'class' => 'rounded-r-none py-3 px-8',
        ],
        'sale_flash' => [
            'class' => 'absolute text-xs font-semibold text-foreground bg-accent top-2 right-2 px-2.5 py-1 rounded-full z-10 uppercase tracking-wide',
        ],
    ],
    'archive-product' => [
        'product_grid' => [
            'class' => 'grid grid-cols-1 gap-y-4 sm:grid-cols-2 sm:gap-x-6 sm:gap-y-10 lg:gap-x-8 xl:grid-cols-3',
        ],
        'product_title' => [
            'class' => 'text-sm font-medium text-foreground',
        ],
        'product_facets' => [
            'count' => [
                'class' => 'product-count',
            ],
            'sort' => [
                'class' => 'sort-product group inline-flex justify-center items-center text-sm font-medium text-muted',
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
        // Use a simplified layout (no main nav, no footer links) for cart, checkout & thank-you pages
        'simplified_layout' => true,
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
            'class' => 'flex-none w-20 h-20 object-center object-cover bg-surface rounded-lg sm:w-40 sm:h-40',
        ],
    ],
    'related' => [
        'class' => 'mt-8 grid grid-cols-1 gap-y-12 sm:grid-cols-2 sm:gap-x-6 lg:grid-cols-4 xl:gap-x-8',
    ],
    'myaccount' => [
        'order_status_colors' => [
            'completed'  => 'bg-success-light text-success ring-success/20',
            'processing' => 'bg-info-light text-info ring-info/20',
            'on-hold'    => 'bg-warning-light text-warning ring-warning/20',
            'pending'    => 'bg-warning-light text-warning ring-warning/20',
            'cancelled'  => 'bg-error-light text-error ring-error/20',
            'refunded'   => 'bg-surface-alt text-muted ring-outline',
            'failed'     => 'bg-error-light text-error ring-error/20',
        ],
        'icons' => [
            // Navigation icons (also used in dashboard cards)
            'dashboard'       => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>',
            'orders'          => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>',
            'downloads'       => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>',
            'edit-address'    => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" /></svg>',
            'edit-account'    => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>',
            'payment-methods' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" /></svg>',
            'customer-logout' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" /></svg>',

            // Address section
            'billing'         => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" /></svg>',
            'shipping'        => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" /></svg>',

            // Action icons
            'edit'            => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>',
            'chevron-left'    => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>',
            'chevron-right'   => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>',

            // Empty state icons (larger, stroke-width 1)
            'empty-orders'    => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="size-12"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>',
            'empty-downloads' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="size-12"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>',
            'empty-payment'   => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="size-12"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" /></svg>',

            // Timeline
            'comment'         => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" /></svg>',

            // Default fallback for unknown navigation endpoints
            'default'         => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>',
        ],
    ],
    'review' => [
        'form' => [
            'wrapper' => [
                'class' => 'mt-4 mb-6',
            ],
            'label' => [
                'class' => 'block text-sm font-medium text-muted mb-1',
            ],
            'textarea' => [
                'class' => 'px-3.5 py-2.5 bg-white-gray mt-1 block w-full sm:text-sm border border-outline rounded-md',
            ],
            'input' => [
                'class' => 'px-3.5 py-2.5 mt-1 bg-white-gray block w-full sm:text-sm border border-outline rounded-md',
            ],
            'submit' => [
                'wrapper' => [
                    'class' => 'pt-4',
                ],
                'class' => 'w-full sm:w-auto inline-flex justify-center py-2.5 px-6 border border-transparent shadow-xs text-sm font-semibold rounded-lg text-white bg-primary hover:bg-primary-hover focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-ring transition-colors',
            ],
            'cookie' => [
                'wrapper' => [
                    'class' => 'py-3 flex items-start',
                ],
                'label' => [
                    'class' => 'text-sm font-medium text-muted mb-1 ml-3',
                ],
                'checkbox' => [
                    'class' => 'focus:ring-ring h-4 w-4 text-primary border-outline rounded-xs',
                ],
            ],
        ],
    ],
];
