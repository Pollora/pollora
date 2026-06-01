{{-- *
 * Shipping Methods Display
 *
 * In 2.1 we show methods per package. This allows for multiple methods per order if so desired.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-shipping.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.8.0
  --}}
{{-- docs.woocommerce.com/document/template-structure/ --}}
@php
	$formatted_destination    = isset( $formatted_destination ) ? $formatted_destination : WC()->countries->get_formatted_address( $package['destination'], ', ' );
	$has_calculated_shipping  = ! empty( $has_calculated_shipping );
	$show_shipping_calculator = ! empty( $show_shipping_calculator );
	$calculator_text          = '';
@endphp

<div class="woocommerce-shipping-totals shipping">
	<div class="shipping border-t border-outline pt-4">
		@if ( $available_methods )
			<h3 class="text-lg lg:text-xl font-bold text-foreground mb-4 lg:mb-6">
				{!! wp_kses_post( $package_name ) !!}
			</h3>
			<ul class="woocommerce-shipping-methods list-none pl-0 my-4 border border-outline rounded-xl [&>li:first-child]:rounded-t-xl [&>li:last-child]:rounded-b-xl">
				@foreach ( $available_methods as $method )
					@php $is_chosen = $method->id === $chosen_method; @endphp
					<li class="border-b border-outline last:border-b-0 has-[:checked]:shadow-[inset_0_0_0_1.5px_var(--color-foreground)]">
						<label for="shipping_method_{{ $index }}_{{ esc_attr( sanitize_title( $method->id ) ) }}" class="flex items-center gap-3 px-4 py-3.5 cursor-pointer hover:bg-surface/50 transition-colors">
							@if ( count( $available_methods ) > 1 )
								<input type="radio" name="shipping_method[{{ $index }}]" data-index="{{ $index }}" id="shipping_method_{{ $index }}_{{ esc_attr( sanitize_title( $method->id ) ) }}" value="{{ esc_attr( $method->id ) }}" class="shipping_method shrink-0 h-4 w-4 text-foreground border-outline focus:ring-foreground/20" @if ( $is_chosen ) checked="checked" @endif />
							@else
								<input type="hidden" name="shipping_method[{{ $index }}]" data-index="{{ $index }}" id="shipping_method_{{ $index }}_{{ esc_attr( sanitize_title( $method->id ) ) }}" value="{{ esc_attr( $method->id ) }}" class="shipping_method" />
							@endif
							<span class="flex-1 text-sm font-medium text-foreground">
								{!! wc_cart_totals_shipping_method_label( $method ) !!}
							</span>
						</label>
						@php do_action('woocommerce_after_shipping_rate', $method, $index); @endphp
					</li>
				@endforeach
			</ul>


			@if ( is_cart() )
				<p class="woocommerce-shipping-destination mt-4 text-center text-sm text-muted sm:mt-0 sm:text-left">
					{{--  Translators: $s shipping destination. --}}
					@if ( $formatted_destination )
						{!! sprintf( esc_html__( 'Shipping to %s.', 'woocommerce' ) . ' ', '<strong>' . esc_html( $formatted_destination ) . '</strong>' ) !!}
						@php
							$calculator_text = esc_html__( 'Change address', 'woocommerce' );
						@endphp
					@else
						{!! wp_kses_post( apply_filters( 'woocommerce_shipping_estimate_html', __( 'Shipping options will be updated during checkout.', 'woocommerce' ) ) ) !!}
					@endif
				</p>
			@endif

			{{--  Translators: $s shipping destination. --}}

		@elseif ( ! $has_calculated_shipping || ! $formatted_destination )
			<p class="woocommerce-shipping-destination mt-4 text-center text-sm text-muted sm:mt-0 sm:text-left">
				@if ( is_cart() && 'no' === get_option( 'woocommerce_enable_shipping_calc' ) )
					{!! wp_kses_post( apply_filters( 'woocommerce_shipping_not_enabled_on_cart_html', __( 'Shipping costs are calculated during checkout.', 'woocommerce' ) ) ) !!}
				@else
					{!! wp_kses_post( apply_filters( 'woocommerce_shipping_may_be_available_html', __( 'Enter your address to view shipping options.', 'woocommerce' ) ) ) !!}
				@endif
			</p>
		@elseif ( ! is_cart() )
			<p class="woocommerce-shipping-destination mt-4 text-center text-sm text-muted sm:mt-0 sm:text-left">
				{!! wp_kses_post( apply_filters( 'woocommerce_no_shipping_available_html', __( 'There are no shipping options available. Please ensure that your address has been entered correctly, or contact us if you need any help.', 'woocommerce' ) ) ) !!}
			</p>
		@else
			{!! wp_kses_post( apply_filters( 'woocommerce_cart_no_shipping_available_html', sprintf( esc_html__( 'No shipping options were found for %s.', 'woocommerce' ) . ' ', '<strong>' . esc_html( $formatted_destination ) . '</strong>' ), $formatted_destination ) ) !!}
			@php
				$calculator_text = esc_html__( 'Enter a different address', 'woocommerce' );
			@endphp
		@endif

		@if ( $show_package_details )
			<p class="woocommerce-shipping-contents woocommerce-shipping-destination mt-4 text-center text-sm text-muted sm:mt-0 sm:text-left">
				{{ $package_details }}
			</p>
		@endif

		@if ( $show_shipping_calculator )
			@php woocommerce_shipping_calculator( $calculator_text ); @endphp
		@endif
	</div>
</div>
