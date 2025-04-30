{{-- *
 * Checkout Payment Section
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.3
  --}}
{{-- docs.woocommerce.com/document/template-structure/ --}}
@php
	if ( ! is_ajax() ) {
		do_action( 'woocommerce_review_order_before_payment' );
	}
@endphp
<div id="payment" class="woocommerce-checkout-payment mt-5 border-t border-gray-200 pt-5 px-4 sm:px-6">
	<h3 class="text-lg font-medium text-gray-900">{{ __('Payment', 'woocommerce') }}</h3>
	@if ( WC()->cart->needs_payment() )
		<ul class="wc_payment_methods payment_methods methods">
			@if ( ! empty( $available_gateways ) )
				@foreach ( $available_gateways as $gateway )
					{!! wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) ) !!}
				@endforeach
			@else
				<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">
					{!! apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) : esc_html__( 'Please fill in your details above to see available payment methods.', 'woocommerce' ) ) !!}
				</li>
			@endif
		</ul>
	@endif

	<div class="form-row place-order">
		<noscript>
			{{--  translators: $1 and $2 opening and closing emphasis tags respectively  --}}
			{!! sprintf( esc_html__( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the %1$sUpdate Totals%2$s button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce' ), '<em>', '</em>' ) !!}
			<br/><button type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="@php esc_attr_e( 'Update totals', 'woocommerce' ); @endphp">@php esc_html_e( 'Update totals', 'woocommerce' ); @endphp</button>
		</noscript>

		{!! wc_get_template( 'checkout/terms.php' )!!}


		<div class="mt-5 border-t border-gray-200 py-6">
			@php do_action( 'woocommerce_review_order_before_submit' ); @endphp
			{!! apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="button alt w-full bg-indigo-600 border border-transparent rounded-md shadow-xs py-3 px-4 text-base font-medium text-white hover:bg-indigo-700 focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-indigo-500" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>' ) !!}
			@php do_action( 'woocommerce_review_order_after_submit' ); @endphp
		</div>

		@php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); @endphp
	</div>
</div>

@php
	if ( ! is_ajax() ) {
		do_action( 'woocommerce_review_order_after_payment' );
	}
@endphp
