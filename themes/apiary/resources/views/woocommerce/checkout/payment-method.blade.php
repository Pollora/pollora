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


@endphp
<li class="wc_payment_method payment_method_{!! esc_attr( $gateway->id ) !!} border-b border-outline last:border-b-0 has-[:checked]:shadow-[inset_0_0_0_1.5px_var(--color-foreground)]">
	<label for="payment_method_{!! esc_attr( $gateway->id ) !!}" class="flex items-center gap-3 px-4 py-3.5 cursor-pointer hover:bg-surface/50 transition-colors">
		<input id="payment_method_{!! esc_attr( $gateway->id ) !!}" type="radio" class="input-radio shrink-0 h-4 w-4 text-foreground border-outline focus:ring-foreground/20" name="payment_method" value="{!! esc_attr( $gateway->id ) !!}" @php checked( $gateway->chosen, true ); @endphp data-order_button_text="{!! esc_attr( $gateway->order_button_text ) !!}" />
		<span class="text-sm font-medium text-foreground flex items-center gap-2">
			{!! $gateway->get_title() !!}
			{!! $gateway->get_icon() !!}
		</span>
	</label>

	@if ( $gateway->has_fields() || $gateway->get_description() )
		<div class="payment_box payment_method_{!! esc_attr( $gateway->id ) !!} px-4 pb-4 pt-0 text-sm text-muted leading-relaxed" @if ( ! $gateway->chosen )style="display:none;"@endif>
			@php $gateway->payment_fields(); @endphp
		</div>
	@endif
</li>
