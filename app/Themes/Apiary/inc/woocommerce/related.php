<?php

use Pollora\Support\Facades\Action;
use Pollora\Support\Facades\Filter;

Action::remove('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
Action::add('woocommerce_after_single_product', 'woocommerce_output_related_products', 1);

if (! function_exists('th_inject_related_product_classes')) {
    /**
     * Inject custom css classes into the related product wrapper
     */
    function th_inject_related_product_classes(string $output): string
    {
        $related_class = config('woocommerce.related.class', 'mt-8 grid grid-cols-1 gap-y-12 sm:grid-cols-2 sm:gap-x-6 lg:grid-cols-4 xl:gap-x-8');
        $output = preg_replace('/products columns-[0-9]*/', $related_class, $output);

        return $output;
    }
}

Action::add('woocommerce_before_related_products', fn () => Filter::add('woocommerce_product_loop_start', 'th_inject_related_product_classes'));
Action::add('woocommerce_after_related_products', fn () => Filter::remove('woocommerce_product_loop_start', 'th_inject_related_product_classes'));
