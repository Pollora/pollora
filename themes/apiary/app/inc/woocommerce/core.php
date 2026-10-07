<?php

declare(strict_types=1);

/**
 * WooCommerce core activation check.
 *
 * @package Theme\Apiary
 */

use Pollora\Support\Facades\Action;

// Note: WC_Template_Loader::init must NOT be removed — Pollora relies on it
// to resolve WooCommerce Blade templates via the template_include filter.

/**
 * Check if WooCommerce is activated
 */
if (! function_exists('is_woocommerce_activated')) {
    function is_woocommerce_activated(): bool
    {
        return class_exists('woocommerce');
    }
}

// The layout already opens div#primary and main#main; WooCommerce's default
// wrapper opened them a second time on every shop page: two main landmarks,
// two elements with the same id.
Action::add('woocommerce_init', function () {
    Action::remove('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10, 0);
    Action::remove('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10, 0);
});
