{{-- *
 * Checkout shipping information form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-shipping.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 * @global WC_Checkout $checkout
  --}}
{{-- docs.woocommerce.com/document/template-structure/ --}}
@if ( true === WC()->cart->needs_shipping_address() )
<div class="woocommerce-shipping-fields mt-8 pt-8">
		<div id="ship-to-different-address">
			<label for="ship-to-different-address-checkbox"
				   class="woocommerce-form__label woocommerce-form__label-for-checkbox flex items-center gap-3 cursor-pointer">
				<input id="ship-to-different-address-checkbox"
					   class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox h-4 w-4 rounded border-outline text-foreground focus:ring-foreground/20" type="checkbox"
					   name="ship_to_different_address"
					   value="1" @php checked( apply_filters( 'woocommerce_ship_to_different_address_checked', 'shipping' === get_option( 'woocommerce_ship_to_destination' ) ? 1 : 0, $checkout ), 1 ); @endphp />
				<span class="text-sm font-medium text-foreground">
					@php esc_html_e( 'Ship to a different address?', 'woocommerce' ); @endphp
				</span>
			</label>
		</div>

		<div class="shipping_address">
			@php do_action( 'woocommerce_before_checkout_shipping_form', $checkout ); @endphp
			<div class="woocommerce-shipping-fields__field-wrapper mt-6 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
				@php
					$fields = $checkout->get_checkout_fields( 'shipping' );
				@endphp
				@foreach ( $fields as $key => $field )
					{!! woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ) !!}
				@endforeach
			</div>
			@php do_action( 'woocommerce_after_checkout_shipping_form', $checkout ); @endphp
		</div>
</div>
@endif

{{-- Order notes moved to payment.blade.php (before terms/submit, like Gutenberg) --}}
@php do_action( 'woocommerce_before_order_notes', $checkout ); @endphp
@php do_action( 'woocommerce_after_order_notes', $checkout ); @endphp