{{-- *
 * Output a single payment method
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment-method.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.5.0
  --}}
{{-- docs.woocommerce.com/document/template-structure/ --}}
@php


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
@endphp
<li class="wc_payment_method payment_method_{!! esc_attr( $gateway->id ) !!} mt-4">
	<div class="flex items-center">
		<input id="payment_method_{!! esc_attr( $gateway->id ) !!}" type="radio" class="input-radio focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" name="payment_method" value="{!! esc_attr( $gateway->id ) !!}" @php checked( $gateway->chosen, true ); @endphp data-order_button_text="{!! esc_attr( $gateway->order_button_text ) !!}" />
		<label for="payment_method_{!! esc_attr( $gateway->id ) !!}" class="ml-3 block text-sm font-medium text-gray-700">
			{!! $gateway->get_title() !!}
			{!! $gateway->get_icon() !!}
		</label>
	</div>

	@if ( $gateway->has_fields() || $gateway->get_description() )
		<div class="payment_box payment_method_{!! esc_attr( $gateway->id ) !!} mt-2 text-sm text-gray-500" @if ( ! $gateway->chosen )style="display:none;"@endif>
			@php $gateway->payment_fields(); @endphp
		</div>
	@endif

</li>
