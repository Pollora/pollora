{{-- *
 * Shipping Calculator
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/shipping-calculator.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.0.0
  --}}
{{-- docs.woocommerce.com/document/template-structure/ --}}
@php


defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_shipping_calculator' ); @endphp

<form class="woocommerce-shipping-calculator pt-2" action="{!! esc_url( wc_get_cart_url() ) !!}" method="post">

	@php printf( '<a href="#" class="shipping-calculator-button text-sm font-medium text-indigo-600 hover:text-indigo-500">%s</a>', esc_html( ! empty( $button_text ) ? $button_text : __( 'Calculate shipping', 'woocommerce' ) ) ); @endphp

	<section class="shipping-calculator-form grid grid-cols-12 gap-y-6 gap-x-4 pt-2" style="display:none;">

		@if ( apply_filters( 'woocommerce_shipping_calculator_enable_country', true ) )
			<p class="form-row form-row-wide col-span-full" id="calc_shipping_country_field">
				<select name="calc_shipping_country" id="calc_shipping_country" class="country_to_state country_select" rel="calc_shipping_state">
					<option value="default">@php esc_html_e( 'Select a country / region&hellip;', 'woocommerce' ); @endphp</option>
					@php
					foreach ( WC()->countries->get_shipping_countries() as $key => $value ) {
						echo '<option value="' . esc_attr( $key ) . '"' . selected( WC()->customer->get_shipping_country(), esc_attr( $key ), false ) . '>' . esc_html( $value ) . '</option>';
					}
					@endphp
				</select>
			</p>
		@endif

		@if ( apply_filters( 'woocommerce_shipping_calculator_enable_state', true ) )

			<p class="form-row form-row-wide col-span-full" id="calc_shipping_state_field">
				@php
				$current_cc = WC()->customer->get_shipping_country();
				$current_r  = WC()->customer->get_shipping_state();
				$states     = WC()->countries->get_states( $current_cc );

				if ( is_array( $states ) && empty( $states ) ) {
					@endphp
					<input type="hidden" name="calc_shipping_state" id="calc_shipping_state" placeholder="@php esc_attr_e( 'State / County', 'woocommerce' ); @endphp" />
					@php
				} elseif ( is_array( $states ) ) {
					@endphp
					<span>
						<select name="calc_shipping_state" class="state_select" id="calc_shipping_state" data-placeholder="@php esc_attr_e( 'State / County', 'woocommerce' ); @endphp">
							<option value="">@php esc_html_e( 'Select an option&hellip;', 'woocommerce' ); @endphp</option>
							@php
							foreach ( $states as $ckey => $cvalue ) {
								echo '<option value="' . esc_attr( $ckey ) . '" ' . selected( $current_r, $ckey, false ) . '>' . esc_html( $cvalue ) . '</option>';
							}
							@endphp
						</select>
					</span>
					@php
				} else {
					@endphp
					<input type="text" class="input-text" value="{!! esc_attr( $current_r ) !!}" placeholder="@php esc_attr_e( 'State / County', 'woocommerce' ); @endphp" name="calc_shipping_state" id="calc_shipping_state" />
					@php
				}
				@endphp
			</p>
		
		@endif

		@if ( apply_filters( 'woocommerce_shipping_calculator_enable_city', true ) )
			<p class="form-row form-row-wide col-span-full sm:col-span-6" id="calc_shipping_city_field">
				<input type="text" class="input-text block w-full border-gray-300 rounded-md shadow-xs focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{!! esc_attr( WC()->customer->get_shipping_city() ) !!}" placeholder="@php esc_attr_e( 'City', 'woocommerce' ); @endphp" name="calc_shipping_city" id="calc_shipping_city" />
			</p>
		@endif

		@if ( apply_filters( 'woocommerce_shipping_calculator_enable_postcode', true ) )
			<p class="form-row form-row-wide col-span-full sm:col-span-6" id="calc_shipping_postcode_field">
				<input type="text" class="input-text block w-full border-gray-300 rounded-md shadow-xs focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{!! esc_attr( WC()->customer->get_shipping_postcode() ) !!}" placeholder="@php esc_attr_e( 'Postcode / ZIP', 'woocommerce' ); @endphp" name="calc_shipping_postcode" id="calc_shipping_postcode" />
			</p>
		@endif


		<p class="col-span-full">
			<button type="submit" name="calc_shipping" value="1" class="button bg-gray-200 text-sm font-medium text-gray-600 rounded-md px-4 py-2 hover:bg-gray-300 focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-indigo-500">
				{{ __( 'Update', 'woocommerce' ) }}
			</button></p>
		@php wp_nonce_field( 'woocommerce-shipping-calculator', 'woocommerce-shipping-calculator-nonce' ); @endphp
	</section>
</form>

@php do_action( 'woocommerce_after_shipping_calculator' ); @endphp
