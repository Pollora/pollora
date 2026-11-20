{{--
 * Mini-cart action buttons (checkout + continue shopping).
 *
 * Replaces the default WooCommerce `woocommerce_widget_shopping_cart_button_view_cart`
 * and `woocommerce_widget_shopping_cart_proceed_to_checkout` callbacks with a
 * single themed Blade partial hooked into `woocommerce_widget_shopping_cart_buttons`.
 *
 * @see app/inc/woocommerce/cart.php  Hook registration
 * @package Theme\Apiary
 --}}

<a href="{{ esc_url( wc_get_checkout_url() ) }}"
   class="flex justify-center items-center w-full px-6 py-3 rounded-lg text-sm font-semibold text-white no-underline bg-primary hover:bg-primary-hover shadow-xs transition-colors">
    {{ esc_html__( 'Checkout', 'woocommerce' ) }}
</a>

<div class="text-center">
    <button type="button"
            class="text-sm text-muted hover:text-foreground transition-colors"
            @click="window.dispatchEvent(new CustomEvent('panel-close'))">
        {{ __('or', 'apiary') }}
        <span class="font-medium text-primary hover:text-primary-hover">{{ esc_html__( 'Continue shopping', 'woocommerce' ) }}</span>
        <span aria-hidden="true"> &rarr;</span>
    </button>
</div>