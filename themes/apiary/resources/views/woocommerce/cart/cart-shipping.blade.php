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
 * @version 3.6.0
  --}}
{{-- docs.woocommerce.com/document/template-structure/ --}}
@php
	$formatted_destination    = isset( $formatted_destination ) ? $formatted_destination : WC()->countries->get_formatted_address( $package['destination'], ', ' );
	$has_calculated_shipping  = ! empty( $has_calculated_shipping );
	$show_shipping_calculator = ! empty( $show_shipping_calculator );
	$calculator_text          = '';
@endphp

<div class="woocommerce-shipping-totals shipping">
	<div class="shipping border-t border-gray-200 pt-4">
		@if ( $available_methods )
			<div class="text-lg font-medium text-gray-900">
				{!! wp_kses_post( $package_name ) !!}
			</div>
			@if ( $available_methods )
				@php
					$initialIndex = wc_get_shipping_method_index($chosen_method, $available_methods)
				@endphp
				<div class="woocommerce-shipping-methods my-4 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4" x-data="window.Components.radioGroup({ initialCheckedIndex: {{ $initialIndex }} })" x-init="init()">
					@foreach ( $available_methods as $method )
						<label x-radio-group-option="" class="relative bg-white border rounded-lg shadow-xs p-4 flex cursor-pointer focus:outline-hidden border-gray-300 undefined" x-description="Checked: &quot;border-transparent&quot;, Not Checked: &quot;border-gray-300&quot;	Active: &quot;ring-2 ring-indigo-500&quot;" :class="{ 'border-transparent': (value === '{{ esc_attr( $method->id ) }}'), 'border-gray-300': !(value === '{{ esc_attr( $method->id ) }}'), 'ring-2 ring-indigo-500': (active === '{{ esc_attr( $method->id ) }}'), 'undefined': !(active === 'Standard') }">
							<input type="radio" x-model="value" name="shipping_method[{{ $index }}]" data-index="{{ $index }}" id="shipping_method_{{ $index }}_{{ esc_attr( sanitize_title( $method->id ) ) }}" value="{{ esc_attr( $method->id ) }}" class="sr-only" aria-labelledby="delivery-method-0-label" aria-describedby="delivery-method-0-description-0 delivery-method-0-description-1">
							<div class="flex-1 flex">
								<div class="flex flex-col">
									<span id="delivery-method-0-label" class="block text-sm font-medium text-gray-900">
									  {!! $method->get_label() !!}
									</span>
									@if (isset($method->description))
										<span id="delivery-method-{{ $index }}-description-0" class="mt-1 flex items-center text-sm text-gray-500">
											  {{ $method->description }}
										</span>
									@endif
									<span id="delivery-method-{{ $index }}-description-1" class="mt-6 text-sm font-medium text-gray-900">
										@if ($method->cost === '0.00')
											{{ __('Free', 'woocommerce') }}
										@elseif ( WC()->cart->display_prices_including_tax() )
											{!! wc_price( $method->cost + $method->get_shipping_tax() ) !!}
											@if ( $method->get_shipping_tax() > 0 && ! wc_prices_include_tax() )
												<small class="tax_label">{!! WC()->countries->inc_tax_or_vat() !!}</small>
											@endif
										@else
											{!! wc_price( $method->cost ) !!}
											@if ( $method->get_shipping_tax() > 0 && ! wc_prices_include_tax() )
												<small class="tax_label">{!! WC()->countries->ex_tax_or_vat() !!}</small>
											@endif
										@endif
									</span>
								</div>
							</div>
							<svg class="h-5 w-5 text-indigo-600 hidden" x-description="Not Checked: &quot;hidden&quot;
	Heroicon name: solid/check-circle" :class="{ 'hidden': !(value === '{{ esc_attr( $method->id ) }}'), 'undefined': (value === '{{ esc_attr( $method->id ) }}') }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
								<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
							</svg>
							<div class="absolute -inset-px rounded-lg pointer-events-none border-2 border-transparent" aria-hidden="true" x-description="Active: &quot;border&quot;, Not Active: &quot;border-2&quot;	Checked: &quot;border-indigo-500&quot;, Not Checked: &quot;border-transparent&quot;" :class="{ 'border': (active === '{{ esc_attr( $method->id ) }}'), 'border-2': !(active === '{{ esc_attr( $method->id ) }}'), 'border-indigo-500': (value === '{{ esc_attr( $method->id ) }}'), 'border-transparent': !(value === '{{ esc_attr( $method->id ) }}') }"></div>
						</label>
						@php do_action('woocommerce_after_shipping_rate', $method, $index); @endphp
					@endforeach
				</div>
			@endif


			@if ( is_cart() )
				<p class="woocommerce-shipping-destination mt-4 text-center text-sm text-gray-500 sm:mt-0 sm:text-left">
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
			<p class="woocommerce-shipping-destination mt-4 text-center text-sm text-gray-500 sm:mt-0 sm:text-left">
				@if ( is_cart() && 'no' === get_option( 'woocommerce_enable_shipping_calc' ) )
					{!! wp_kses_post( apply_filters( 'woocommerce_shipping_not_enabled_on_cart_html', __( 'Shipping costs are calculated during checkout.', 'woocommerce' ) ) ) !!}
				@else
					{!! wp_kses_post( apply_filters( 'woocommerce_shipping_may_be_available_html', __( 'Enter your address to view shipping options.', 'woocommerce' ) ) ) !!}
				@endif
			</p>
		@elseif ( ! is_cart() )
			<p class="woocommerce-shipping-destination mt-4 text-center text-sm text-gray-500 sm:mt-0 sm:text-left">
				{!! wp_kses_post( apply_filters( 'woocommerce_no_shipping_available_html', __( 'There are no shipping options available. Please ensure that your address has been entered correctly, or contact us if you need any help.', 'woocommerce' ) ) ) !!}
			</p>
		@else
			{!! wp_kses_post( apply_filters( 'woocommerce_cart_no_shipping_available_html', sprintf( esc_html__( 'No shipping options were found for %s.', 'woocommerce' ) . ' ', '<strong>' . esc_html( $formatted_destination ) . '</strong>' ) ) ) !!}
			@php
				$calculator_text = esc_html__( 'Enter a different address', 'woocommerce' );
			@endphp
		@endif

		@if ( $show_package_details )
			<p class="woocommerce-shipping-contents woocommerce-shipping-destination mt-4 text-center text-sm text-gray-500 sm:mt-0 sm:text-left">
				{{ $package_details }}
			</p>
		@endif

		@if ( $show_shipping_calculator )
			@php woocommerce_shipping_calculator( $calculator_text ); @endphp
		@endif
	</div>
</div>
