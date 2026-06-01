<?php

declare(strict_types=1);

/**
 * WooCommerce cart customizations and AJAX fragments.
 *
 * Global scope is intentional — functions are either WooCommerce overrides
 * (detected via function_exists()) or registered as hook callbacks.
 *
 * @package Theme\Apiary
 */

use Pollora\Support\Facades\Action;
use Pollora\Support\Facades\Filter;

if (! function_exists('wc_get_cart_item_count')) {
    /**
     * Get the number of items in the cart.
     *
     * @return int Cart item count.
     */
    function wc_get_cart_item_count()
    {
        return WC()->cart->cart_contents_count;
    }
}

// Update the header cart badge count via WooCommerce's AJAX fragment system
Filter::add('woocommerce_add_to_cart_fragments', function ($fragments) {
    $fragments['.cart-count'] = '<span class="cart-count">'.wc_get_cart_item_count().'</span>';

    return $fragments;
});

// Inject a Laravel CSRF token so AJAX add-to-cart requests pass middleware validation
Action::add('woocommerce_before_add_to_cart_button', function (): void {
    echo '<input type="hidden" name="_token" value="'.csrf_token().'">';
});

// Ensure the WC variation selection script is loaded on product pages
Action::add('wp_enqueue_scripts', function (): void {
    wp_enqueue_script('wc-add-to-cart-variation');
});

// Hide individual attribute values from the variation title (e.g. "Hoodie - Red, Large" → "Hoodie")
Filter::add('woocommerce_product_variation_title_include_attributes', '__return_false');

if (! function_exists('wc_cart_item_data_format')) {
    /**
     * Format cart item meta data as a comma-separated "key: value" string.
     *
     * @param  array<int, array{key: string, display: string}>  $item_data  Meta entries.
     * @return string Formatted HTML string (e.g. "Color: Red, Size: L").
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
 * Suppress the mini-cart widget subtotal line.
 *
 * The slide-over cart drawer handles subtotal display in its own Blade template.
 */
function woocommerce_widget_shopping_cart_subtotal(): string
{
    return '';
}

// Replace WooCommerce's default "View cart" button classes with theme-styled Tailwind classes
Filter::add('wc_add_to_cart_message_html', function ($message) {
    return str_replace('button wc-forward wp-element-button', 'button wc-forward inline-block w-auto bg-primary border border-transparent rounded-md py-3 px-8 text-base font-medium text-white hover:bg-primary-hover focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-ring mr-4', $message);
});

// Replace the default mini-cart buttons (view cart + proceed to checkout) with a single Blade partial
Action::add('woocommerce_init', function (): void {
    Action::remove('woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart', 10);
    Action::remove('woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_proceed_to_checkout', 20);

    Action::add('woocommerce_widget_shopping_cart_buttons', function (): void {
        echo view('woocommerce.cart.mini-cart-buttons')->render();
    }, 10);
});
