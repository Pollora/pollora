<?php

declare(strict_types=1);

/**
 * WooCommerce related products layout customizations.
 *
 * Global scope is intentional — th_inject_related_product_classes() is passed
 * as a string callback to WooCommerce filters.
 *
 * @package Theme\Apiary
 */

use Pollora\Support\Facades\Action;
use Pollora\Support\Facades\Filter;

// Move related products from inside the product summary to after the entire product block
Action::add('woocommerce_init', function () {
    Action::remove('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
    Action::add('woocommerce_after_single_product', 'woocommerce_output_related_products', 1);
});

if (! function_exists('th_inject_related_product_classes')) {
    /**
     * Replace WooCommerce's default `products columns-N` classes with a Tailwind grid.
     *
     * @param  string  $output  The opening `<ul>` tag from `woocommerce_product_loop_start`.
     * @return string Modified markup with configurable grid classes.
     *
     * @see config('theme.woocommerce.related.class')
     */
    function th_inject_related_product_classes(string $output): string
    {
        $related_class = config('theme.woocommerce.related.class', 'mt-8 grid grid-cols-1 gap-y-12 sm:grid-cols-2 sm:gap-x-6 lg:grid-cols-4 xl:gap-x-8');
        $output = preg_replace('/products columns-[0-9]*/', $related_class, $output);

        return $output;
    }
}

// Apply the grid class override only around the related products section (not the main shop loop)
Action::add('woocommerce_before_related_products', fn () => Filter::add('woocommerce_product_loop_start', 'th_inject_related_product_classes'));
Action::add('woocommerce_after_related_products', fn () => Filter::remove('woocommerce_product_loop_start', 'th_inject_related_product_classes'));

// Display 4 related products in a 4-column grid
Filter::add('woocommerce_output_related_products_args', function (array $args): array {
    $args['posts_per_page'] = 4;
    $args['columns'] = 4;
    return $args;
});
