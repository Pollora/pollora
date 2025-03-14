<?php

use Pollora\Support\Facades\Action;

// Remove the default WooCommerce Template Loader
Action::remove('init', [WC_Template_Loader::class, 'init'], 10);

/**
 * Check if WooCommerce is activated
 */
if (! function_exists('is_woocommerce_activated')) {
    function is_woocommerce_activated(): bool
    {
        return class_exists('woocommerce');
    }
}
