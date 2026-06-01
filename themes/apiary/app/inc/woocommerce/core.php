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
