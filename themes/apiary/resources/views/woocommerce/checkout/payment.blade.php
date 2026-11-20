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
 * @version 9.8.0
  --}}
{{-- docs.woocommerce.com/document/template-structure/ --}}
@php
	if ( ! wp_doing_ajax() ) {
		do_action( 'woocommerce_review_order_before_payment' );
	}
@endphp
<div id="payment" class="woocommerce-checkout-payment mt-6 lg:mt-8">
	<h3 class="text-lg lg:text-xl font-bold text-foreground mb-3 lg:mb-4">{{ __('Payment options', 'woocommerce') }}</h3>
	@if ( WC()->cart->needs_payment() )
		<ul class="wc_payment_methods payment_methods methods list-none pl-0 border border-outline rounded-xl [&>li:first-child]:rounded-t-xl [&>li:last-child]:rounded-b-xl">
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

	{{-- Order notes — checkbox toggle like Gutenberg --}}
	@if ( apply_filters( 'woocommerce_enable_order_notes_field', 'yes' === get_option( 'woocommerce_enable_order_comments', 'yes' ) ) )
		<div class="woocommerce-additional-fields mt-6" x-data="{ noteOpen: false }">
			<label class="flex items-center gap-3 cursor-pointer">
				<input type="checkbox" x-model="noteOpen" class="h-4 w-4 rounded border-outline text-foreground focus:ring-foreground/20" />
				<span class="text-sm text-foreground">{{ __( 'Add a note to your order', 'woocommerce' ) }}</span>
			</label>
			<div x-show="noteOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-cloak class="woocommerce-additional-fields__field-wrapper mt-4">
				@php
					$checkout = WC()->checkout();
					foreach ( $checkout->get_checkout_fields( 'order' ) as $key => $field ) {
						woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
					}
				@endphp
			</div>
		</div>
	@endif

	<div class="form-row place-order">
		<noscript>
			{{--  translators: $1 and $2 opening and closing emphasis tags respectively  --}}
			{!! sprintf( esc_html__( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the %1$sUpdate Totals%2$s button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce' ), '<em>', '</em>' ) !!}
			<br/><button type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="@php esc_attr_e( 'Update totals', 'woocommerce' ); @endphp">@php esc_html_e( 'Update totals', 'woocommerce' ); @endphp</button>
		</noscript>

		{!! wc_get_template( 'checkout/terms.php' )!!}


		{{-- Desktop: inline actions --}}
		<div class="mt-6 pt-6 hidden lg:flex items-center gap-4">
			@php do_action( 'woocommerce_review_order_before_submit' ); @endphp
			<a href="{{ esc_url( wc_get_cart_url() ) }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-muted hover:text-foreground transition-colors shrink-0">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false" class="size-4 fill-current"><path d="M20 11.2H6.8l3.7-3.7-1-1L3.9 12l5.6 5.5 1-1-3.7-3.7H20z"></path></svg>
				{{ __( 'Return to Cart', 'woocommerce' ) }}
			</a>
			{!! apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="button alt flex-1 bg-primary border border-transparent rounded-xl shadow-xs py-3 px-4 text-base font-semibold text-white hover:bg-primary-hover focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-offset-surface focus:ring-ring" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>' ) !!}
			@php do_action( 'woocommerce_review_order_after_submit' ); @endphp
		</div>

		{{-- Mobile: sticky bottom CTA bar --}}
		<div class="classic-checkout-sticky-cta fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-outline px-4 py-3 shadow-[0_-4px_16px_rgb(0_0_0/0.08)] lg:hidden" style="padding-bottom: max(0.75rem, env(safe-area-inset-bottom))">
			<p class="text-center text-[11px] text-muted tracking-wide mb-2">&#x1F512; {{ __( 'Paiement sécurisé', 'woocommerce' ) }}</p>
			<div class="flex items-center gap-3">
				@php do_action( 'woocommerce_review_order_before_submit' ); @endphp
				<a href="{{ esc_url( wc_get_cart_url() ) }}" class="shrink-0 flex items-center justify-center size-11 border border-outline rounded-xl bg-surface text-muted hover:text-foreground hover:border-ring transition-colors" aria-label="{{ esc_attr__( 'Return to Cart', 'woocommerce' ) }}">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false" class="size-5 fill-current"><path d="M20 11.2H6.8l3.7-3.7-1-1L3.9 12l5.6 5.5 1-1-3.7-3.7H20z"></path></svg>
				</a>
				{!! apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="button alt flex-1 bg-primary border border-transparent rounded-xl shadow-xs py-3 px-4 text-[15px] font-semibold text-white hover:bg-primary-hover focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-offset-surface focus:ring-ring min-h-11" name="woocommerce_checkout_place_order" id="place_order_mobile" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>' ) !!}
				@php do_action( 'woocommerce_review_order_after_submit' ); @endphp
			</div>
		</div>

		@php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); @endphp
	</div>
</div>

@php
	if ( ! wp_doing_ajax() ) {
		do_action( 'woocommerce_review_order_after_payment' );
	}
@endphp
