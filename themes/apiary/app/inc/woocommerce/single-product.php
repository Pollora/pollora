<?php

declare(strict_types=1);

/**
 * WooCommerce single product page layout customizations.
 *
 * Global scope is intentional — relies on global wrapper_open/wrapper_close
 * functions and registers multiple hook callbacks.
 *
 * @package Theme\Apiary
 */

use Pollora\Support\Facades\Action;
use Pollora\Support\Facades\Filter;

// Wrap the title + price area in a flex container so they sit side-by-side
Action::add('woocommerce_single_product_summary', function () {
    $summary_wrapper = config('theme.woocommerce.single-product.summary.class', 'flex flex-wrap justify-between items-baseline gap-x-6 gap-y-1');
    wrapper_open($summary_wrapper);
}, 0);
Action::add('woocommerce_single_product_summary', 'wrapper_close', 11);

// Add configurable Tailwind classes to the "Sale!" badge overlay
Filter::add('woocommerce_sale_flash', function ($html): string {
    $sale_flash_class = config('theme.woocommerce.single-product.sale_flash.class', 'absolute text-xs font-semibold text-foreground bg-accent top-2 right-2 px-2.5 py-1 rounded-full z-10 uppercase tracking-wide');

    return str_replace('class="onsale"', 'class="onsale '.$sale_flash_class.'"', $html);
});


Action::add('woocommerce_init', function () {
    // Move star rating below the title+price flex wrapper (priority 12, after wrapper_close at 11)
    Action::remove('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
    Action::add('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 12);

    // Move the sale badge from before the summary to inside the product gallery (overlays the image)
    Action::remove('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash');
    Action::add('woocommerce_before_single_product_gallery', 'woocommerce_show_product_sale_flash');

    // Tabs are rendered as accordions inside content-single-product.blade.php — prevent duplicate rendering
    Action::remove('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);

    // Re-render price with larger typography (sits next to the title in the flex wrapper)
    Action::remove('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
    Action::add('woocommerce_single_product_summary', function () {
        echo '<div class="shrink-0 text-2xl sm:text-3xl font-bold text-foreground [&_del]:text-base [&_del]:font-normal [&_del]:text-subtle">';
        woocommerce_template_single_price();
        echo '</div>';
    }, 10);
});

// Remove disabled tabs based on config (e.g. hide "Additional information" or "Description")
Filter::add('woocommerce_product_tabs', function ($tabs) {
    foreach (array_keys($tabs) as $tabKey) {
        if (! config('theme.woocommerce.single-product.enabled_tabs.'.$tabKey, true)) {
            unset($tabs[$tabKey]);
        }
    }

    return $tabs;
}, 98);

// Visual divider between the title/price/rating block and the short description
Action::add('woocommerce_single_product_summary', function () {
    echo '<div class="border-t border-outline mt-4 pt-4"></div>';
}, 14);

// Render product tabs (Description, Additional Info) as Alpine.js accordions inside the summary.
// The Reviews tab is excluded here — it's rendered separately in content-single-product.blade.php.
Action::add('woocommerce_single_product_summary', function () {
    $product_tabs = apply_filters('woocommerce_product_tabs', []);
    $accordion_tabs = array_filter($product_tabs, fn($key) => $key !== 'reviews', ARRAY_FILTER_USE_KEY);

    if (!empty($accordion_tabs)) {
        echo view('woocommerce.single-product.accordion', ['accordion_tabs' => $accordion_tabs]);
    }
}, 45);

// Quantity input classes are now handled directly in the quantity-input template
