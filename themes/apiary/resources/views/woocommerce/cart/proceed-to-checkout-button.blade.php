{{--
 * Proceed to checkout button
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package Theme\Apiary\WooCommerce
 * @version 7.0.1
 --}}
<a href="{{ esc_url( wc_get_checkout_url() ) }}" class="checkout-button button alt wc-forward block w-full bg-primary border border-transparent rounded-md shadow-xs py-3 px-4 text-base font-medium text-white hover:bg-primary-hover focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-offset-surface focus:ring-ring text-center">
	{{ __('Proceed to checkout', 'woocommerce' ) }}
</a>
