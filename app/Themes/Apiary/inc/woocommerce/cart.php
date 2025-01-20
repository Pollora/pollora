<?php

use Pollora\Support\Facades\Action;
use Pollora\Support\Facades\Filter;

if (! function_exists('wc_get_cart_item_count')) {
    function wc_get_cart_item_count()
    {
        global $woocommerce;

        return $woocommerce->cart->cart_contents_count;
    }
}

// Cart count ajax fragment
Filter::add('woocommerce_add_to_cart_fragments', function ($fragments) {
    $fragments['.cart-count'] = '<span class="cart-count">'.wc_get_cart_item_count().'</span>';

    return $fragments;
});

// Echo a hidden field with csrf token as value
Action::add('woocommerce_before_add_to_cart_button', function (): void {
    echo '<input type="hidden" name="_token" value="'.csrf_token().'">';
});

// Enquey the cart variation script
Action::add('wp_enqueue_scripts', function (): void {
    wp_enqueue_script('wc-add-to-cart-variation');
});

// Remove attribute from the variation title
Filter::add('woocommerce_product_variation_title_include_attributes', '__return_false');

if (! function_exists('wc_cart_item_data_format')) {
    /**
     * Cart item data formatting
     */
    function wc_cart_item_data_format(array $item_data): string
    {
        $formatted_metas = [];
        foreach ($item_data as $key => $meta) {
            $output = sprintf('<span class="wc-item-data"><span class="wc-item-data-key">%s:</span> <span class="wc-item-data-value">%s</span></span>', $meta['key'], $meta['display']);

            $formatted_metas[] = $output;
        }

        return implode(', ', $formatted_metas);
    }
}

/**
 * Override the woocommerce_widget_shopping_cart_subtotal function
 */
function woocommerce_widget_shopping_cart_subtotal(): string
{
    return '';
}

Filter::add('wc_add_to_cart_message_html', function ($message) {
    return str_replace('button wc-forward wp-element-button', 'button wc-forward inline-block w-auto bg-indigo-600 border border-transparent rounded-md py-3 px-8  text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4', $message);
});
