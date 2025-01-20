<?php

use Pollora\Support\Facades\Action;
use Pollora\Support\Facades\Filter;

// Product summary wrapper
Action::add('woocommerce_single_product_summary', function () {
    $summary_wrapper = config('woocommerce.single-product.summary.class', 'flex justify-between');
    wrapper_open($summary_wrapper);
}, 0);
Action::add('woocommerce_single_product_summary', 'wrapper_close', 11);

// Inject custom css classes to the sale flash tag
Filter::add('woocommerce_sale_flash', function ($html): string {
    $sale_flash_class = config('woocommerce.single-product.sale_flash.class', 'absolute font-semibold text-white bg-indigo-600 top-2 right-2 px-2 py-1 rounded-md z-10');

    return str_replace('class="onsale"', 'class="onsale '.$sale_flash_class.'"', $html);
});

// move rating after title/price wrapper
Action::remove('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
Action::add('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 12);

// Move the sale flash position
Action::remove('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash');
Action::add('woocommerce_before_single_product_gallery', 'woocommerce_show_product_sale_flash');

// Maybe remove or not the description tab
Filter::add('woocommerce_product_tabs', function ($tabs) {
    foreach (array_keys($tabs) as $tabKey) {
        if (! config('woocommerce.single-product.enabled_tabs.'.$tabKey, true)) {
            unset($tabs[$tabKey]);
        }
    }

    return $tabs;
}, 98);

// Custom description placement
Action::add('woocommerce_single_product_summary', function () {
    if (! config('woocommerce.single-product.enabled_tabs.description', true)) {
        echo view('woocommerce.single-product.description');
    }
}, 30);

// Add custom css classes to the quantity input
Filter::add('woocommerce_quantity_input_classes', function (array $classes) {
    if (is_singular('product')) {
        $quantity_input_class = config('woocommerce.single-product.quantity_input.class', 'rounded-r-none py-3 px-8');
        $classes[] = $quantity_input_class;
    }

    return $classes;
}, 99);
