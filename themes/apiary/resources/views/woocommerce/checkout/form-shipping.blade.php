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
<div class="woocommerce-shipping-fields mt-10 border-t border-gray-200 pt-10">
	@if ( true === WC()->cart->needs_shipping_address() )
		<div id="ship-to-different-address" class="mb-4">
			<div class="flex items-center">
				<input id="ship-to-different-address-checkbox"
					   class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" type="checkbox"
					   name="ship_to_different_address"
					   value="1" @php checked( apply_filters( 'woocommerce_ship_to_different_address_checked', 'shipping' === get_option( 'woocommerce_ship_to_destination' ) ? 1 : 0, $checkout ), 1 ); @endphp />
				<label for="ship-to-different-address-checkbox"
					   class="woocommerce-form__label woocommerce-form__label-for-checkbox ml-3 font-medium">
					@php esc_html_e( 'Ship to a different address?', 'woocommerce' ); @endphp
				</label>
			</div>
		</div>

		<div class="shipping_address">
			@php do_action( 'woocommerce_before_checkout_shipping_form', $checkout ); @endphp
			<div class="woocommerce-shipping-fields__field-wrapper mt-4 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
				@php
					$fields = $checkout->get_checkout_fields( 'shipping' );
				@endphp
				@foreach ( $fields as $key => $field ) {
					{!! woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ) !!}
				@endforeach
			</div>
			@php do_action( 'woocommerce_after_checkout_shipping_form', $checkout ); @endphp
		</div>
	@endif

</div>
<div class="woocommerce-additional-fields mt-10 border-t border-gray-200 pt-10">
	@php do_action( 'woocommerce_before_order_notes', $checkout ); @endphp

	@if ( apply_filters( 'woocommerce_enable_order_notes_field', 'yes' === get_option( 'woocommerce_enable_order_comments', 'yes' ) ) )
		@if ( ! WC()->cart->needs_shipping() || wc_ship_to_billing_address_only() )
			<h3 class="text-lg font-medium text-gray-900">@php esc_html_e( 'Additional information', 'woocommerce' ); @endphp</h3>
		@endif

		<div class="woocommerce-additional-fields__field-wrapper mt-4">
			@foreach ( $checkout->get_checkout_fields( 'order' ) as $key => $field )
				{!! woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ) !!}
			@endforeach
		</div>
	@endif

	@php do_action( 'woocommerce_after_order_notes', $checkout ); @endphp
</div>