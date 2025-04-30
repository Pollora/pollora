{{-- *
 * Checkout billing information form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-billing.php.
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
<div class="woocommerce-billing-fields">
	
	@if ( wc_ship_to_billing_address_only() && WC()->cart->needs_shipping() )
			<h3 class="text-lg font-medium text-gray-900">@php esc_html_e( 'Billing &amp; Shipping', 'woocommerce' ); @endphp</h3>
	@else
			<h3 class="text-lg font-medium text-gray-900">@php esc_html_e( 'Billing details', 'woocommerce' ); @endphp</h3>
	@endif

	@php do_action( 'woocommerce_before_checkout_billing_form', $checkout ); @endphp

	<div class="woocommerce-billing-fields__field-wrapper mt-4 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
		@php
			$fields = $checkout->get_checkout_fields( 'billing' );

			foreach ( $fields as $key => $field ) {
				woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
			}
		@endphp
	</div>
	@php do_action( 'woocommerce_after_checkout_billing_form', $checkout ); @endphp
</div>

@if ( ! is_user_logged_in() && $checkout->is_registration_enabled() )
	<div class="woocommerce-account-fields">
		@if ( ! $checkout->is_registration_required() )
			<p class="form-row form-row-wide create-account">
				<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
					<input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" id="createaccount" @php checked( ( true === $checkout->get_value( 'createaccount' ) || ( true === apply_filters( 'woocommerce_create_account_default_checked', false ) ) ), true ); @endphp type="checkbox" name="createaccount" value="1" /> <span>@php esc_html_e( 'Create an account?', 'woocommerce' ); @endphp</span>
				</label>
			</p>
		@endif

		@php do_action( 'woocommerce_before_checkout_registration_form', $checkout ); @endphp
		@if ( $checkout->get_checkout_fields( 'account' ) )
			<div class="create-account">
				@foreach ( $checkout->get_checkout_fields( 'account' ) as $key => $field )
									@php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); @endphp
				@endforeach
			</div>
		@endif

		@php do_action( 'woocommerce_after_checkout_registration_form', $checkout ); @endphp
	</div>

@endif

