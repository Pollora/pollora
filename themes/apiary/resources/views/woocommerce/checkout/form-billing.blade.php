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
@php
	$fields = $checkout->get_checkout_fields( 'billing' );
	$val = fn($k) => trim( (string) $checkout->get_value( $k ) );

	// Determine if required address fields are pre-filled → show compact card
	$prefilled = $val('billing_first_name') && $val('billing_last_name')
	          && $val('billing_address_1') && $val('billing_city')
	          && $val('billing_postcode') && $val('billing_country');

	$full_name = trim( $val('billing_first_name') . ' ' . $val('billing_last_name') );
	$countries = WC()->countries->get_countries();
	$country_name = $countries[ $val('billing_country') ] ?? $val('billing_country');
	$address_line = implode( ', ', array_filter([
		$val('billing_address_1'),
		$val('billing_postcode') . ' ' . strtoupper( $val('billing_city') ),
		$country_name,
	]) );
@endphp

{{-- ── Section 1: Contact information ── --}}
<div class="woocommerce-billing-fields">
	<h3 class="text-lg lg:text-xl font-bold text-foreground mb-4 lg:mb-6">{{ __( 'Contact information', 'woocommerce' ) }}</h3>

	@if ( isset( $fields['billing_email'] ) )
		<div class="woocommerce-billing-fields__field-wrapper">
			@php woocommerce_form_field( 'billing_email', $fields['billing_email'], $val('billing_email') ); @endphp
		</div>
	@endif

	{{-- ── Section 2: Billing details (address card / form toggle) ── --}}
	@php
		$billing_alpine = '{ editing: ' . ($prefilled ? 'false' : 'true') . ' }';
		$billing_heading = wc_ship_to_billing_address_only() && WC()->cart->needs_shipping()
			? __( 'Billing & Shipping', 'woocommerce' )
			: __( 'Billing details', 'woocommerce' );
	@endphp
	<div class="mt-8" x-data="{!! $billing_alpine !!}" x-init="jQuery(document.body).on('checkout_error', function() { editing = true })">
		<h3 class="text-lg lg:text-xl font-bold text-foreground mb-4 lg:mb-6">{{ $billing_heading }}</h3>

		@php do_action( 'woocommerce_before_checkout_billing_form', $checkout ); @endphp

		{{-- Address card (compact view — shown when pre-filled and not editing) --}}
		@if ( $prefilled )
			<div x-show="!editing" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="border border-outline rounded-xl p-4 flex items-start justify-between gap-4">
				<address class="not-italic min-w-0">
					<span class="block text-sm font-medium text-foreground">{{ $full_name }}</span>
					<span class="block text-sm text-muted mt-0.5">{{ $address_line }}</span>
				</address>
				<button type="button" @click="editing = true" class="shrink-0 text-sm font-medium text-primary hover:text-primary-hover transition-colors cursor-pointer">
					{{ __( 'Edit', 'woocommerce' ) }}
				</button>
			</div>
		@endif

		{{-- Full form fields (shown when editing or not pre-filled) --}}
		<div x-show="editing" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" @if ( $prefilled ) x-cloak @endif>
			<div class="woocommerce-billing-fields__field-wrapper mt-4 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
				@php
					foreach ( $fields as $key => $field ) {
						// Email already rendered above in Contact information
						if ( $key === 'billing_email' ) continue;
						woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
					}
				@endphp
			</div>
		</div>

		@php do_action( 'woocommerce_after_checkout_billing_form', $checkout ); @endphp
	</div>
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
