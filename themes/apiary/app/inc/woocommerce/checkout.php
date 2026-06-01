<?php

declare(strict_types=1);

/**
 * WooCommerce checkout customizations, conditional tags, and form field styling.
 *
 * Global scope is intentional — conditional tag functions are called directly
 * in Blade templates and guarded with function_exists().
 *
 * @package Theme\Apiary
 */

use Pollora\Support\Facades\Action;
use Pollora\Support\Facades\Filter;

if (! function_exists('is_thank_you_page')) {
    /**
     * Check if the current page is the order-received (thank you) page.
     *
     * @return bool True on the thank-you / order-received endpoint.
     */
    function is_thank_you_page(): bool
    {
        return is_checkout() && ! empty(is_wc_endpoint_url('order-received'));
    }
}

if (! function_exists('is_checkout_form')) {
    /**
     * Check if the current page is the active checkout form (not a WC endpoint like order-pay).
     *
     * @return bool True on the main checkout form page.
     */
    function is_checkout_form(): bool
    {
        return is_checkout() && WC()->query->get_current_endpoint() === '';
    }
}

if (! function_exists('wc_get_shipping_method_index')) {
    /**
     * Get the zero-based index of the chosen shipping method within available methods.
     *
     * @param  string  $chosen_method     The selected method ID (e.g. "flat_rate:1").
     * @param  array   $available_methods Keyed array of available WC_Shipping_Rate objects.
     * @return int Position index, or 0 if not found.
     */
    function wc_get_shipping_method_index(string $chosen_method, array $available_methods): int
    {
        return (int) array_search($chosen_method, array_keys($available_methods));
    }
}

// Add the shipping methods block as an AJAX fragment so it refreshes when the address changes
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

/**
 * Style checkout and address form fields with Tailwind classes.
 *
 * Applies label/input defaults, per-field overrides from config, grid column
 * spans for wide fields, and side-by-side layout for postcode/city pairs.
 *
 * @see config('theme.woocommerce.checkout.fields')
 */
Filter::add('woocommerce_form_field_args', function ($args, $key) {
    $fields = config('theme.woocommerce.checkout.fields');
    $default_label_class = config('theme.woocommerce.checkout.fields.label.default_class', 'block text-sm font-medium text-foreground mb-1.5');
    $default_input_class = config('theme.woocommerce.checkout.fields.input.default_class', 'block w-full rounded-lg border border-outline px-3.5 py-2.5 text-sm text-foreground shadow-xs placeholder:text-subtle focus:border-ring focus:ring-2 focus:ring-ring/20 focus:outline-hidden');

    if (isset($fields[$key])) {
        $args = array_merge_recursive($args, $fields[$key]);
    }
    if (isset($args['label_class']) && $default_label_class) {
        $args['label_class'][] = $default_label_class;
    }

    // Hide order notes label visually — the checkbox already describes the field
    if ($key === 'order_comments') {
        $args['label_class'][] = 'sr-only';
    }
    if (isset($args['input_class']) && $default_input_class) {
        $args['input_class'][] = $default_input_class;
    }

    // Grid column span based on WooCommerce row type
    $row_classes = $args['class'] ?? [];
    if (is_array($row_classes)) {
        $class_string = implode(' ', $row_classes);
    } else {
        $class_string = $row_classes;
    }

    if (str_contains($class_string, 'form-row-wide')) {
        $args['class'][] = 'sm:col-span-2';
    }

    // Address form: make postcode/city, state/city and phone/email side by side
    $half_width_fields = [
        'billing_postcode', 'shipping_postcode',
        'billing_city', 'shipping_city',
        'billing_state', 'shipping_state',
        'billing_phone', 'shipping_phone',
        'shipping_email',
    ];

    if (in_array($key, $half_width_fields, true)) {
        // Remove form-row-wide and sm:col-span-2
        $args['class'] = array_filter($args['class'], fn ($c) => $c !== 'form-row-wide' && $c !== 'sm:col-span-2');
    }

    // Address_2: reduce top spacing to sit tight under address_1
    if (str_contains($key, 'address_2')) {
        $args['class'][] = '-mt-2';
    }

    return $args;
}, 10, 2);

// Remove the default coupon form — replaced by a collapsible panel in review-order.blade.php
Action::add('woocommerce_init', function () {
    Action::remove('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);
});

// Detach payment from the order review sidebar — rendered in the main column via payment.blade.php
Action::add('woocommerce_init', function () {
    Action::remove('woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20);
});
