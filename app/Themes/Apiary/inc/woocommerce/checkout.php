<?php

use Pollora\Support\Facades\Action;
use Pollora\Support\Facades\Filter;

if (! function_exists('is_thank_you_page')) {
    /**
     * Thank you page conditional tags
     */
    function is_thank_you_page(): bool
    {
        return is_checkout() && ! empty(is_wc_endpoint_url('order-received'));
    }
}

if (! function_exists('is_checkout_form')) {
    /**
     * Checkout form conditional tags
     */
    function is_checkout_form(): bool
    {
        return is_checkout() && WC()->query->get_current_endpoint() === '';
    }
}

if (! function_exists('wc_get_shipping_method_index')) {
    /**
     * Get the shipping method index
     */
    function wc_get_shipping_method_index(string $chosen_method, array $available_methods): int
    {
        return (int) array_search($chosen_method, array_keys($available_methods));
    }
}

// Create shippping methods specific ajax fragment
Filter::add('woocommerce_update_order_review_fragments', function ($fragments) {
    // Get checkout payment fragment.
    ob_start();
    ?>
    <div class="shipping-methods-wrapper">
        <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) { ?>
            <?php do_action('woocommerce_review_order_before_shipping'); ?>
            <?php wc_cart_totals_shipping_html(); ?>
            <?php do_action('woocommerce_review_order_after_shipping'); ?>
        <?php } ?>
    </div>
    <?php
    $woocommerce_checkout_shipping = ob_get_clean();
    $fragments['.shipping-methods-wrapper'] = $woocommerce_checkout_shipping;

    return $fragments;
});

// Checkout form field classes
Filter::add('woocommerce_form_field_args', function ($args, $key) {
    $fields = config('woocommerce.checkout.fields');
    $default_label_class = config('woocommerce.checkout.fields.label.default_class', 'block text-sm font-medium text-gray-700 mb-1');
    $default_input_class = config('woocommerce.checkout.fields.label.default_class', 'block w-full border-gray-300 rounded-md shadow-xs focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm');

    if (isset($fields[$key])) {
        $args = array_merge_recursive($args, $fields[$key]);
    }
    if (isset($args['label_class']) && $default_label_class) {
        $args['label_class'][] = $default_label_class;
    }
    if (isset($args['input_class']) && $default_input_class) {
        $args['input_class'][] = $default_input_class;
    }

    return $args;
}, 10, 2);

// Move the checkout coupon form after the totals
Action::add('after_setup_theme', function() {
    Action::remove('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);
    Action::add('woocommerce_review_order_before_totals', 'woocommerce_checkout_coupon_form', 10);
});
