{{-- *
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
  --}}
{{-- docs.woocommerce.com/document/template-structure/ --}}
@php


defined( 'ABSPATH' ) || exit;
@endphp
<div class="shop_table woocommerce-checkout-review-order-table">
	<ul class="divide-y divide-gray-200" role="list">
		@php
			do_action( 'woocommerce_review_order_before_cart_contents' );
        @endphp
		@foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item )
			@php
				$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			@endphp

			@if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) )
				<li class="flex py-6 px-4 sm:px-6 {!! esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ) !!}">
					<div class="flex-shrink-0">
						{!! apply_filters( 'woocommerce_checkout_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key ) !!}
					</div>
					<div class="ml-6 flex-1 flex flex-col">
						<div class="product-name">
							<div class="flex">
								<div class="min-w-0 flex-1">
									{!! wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ) . '&nbsp;' !!}
									{!! apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf( '&times;&nbsp;%s', $cart_item['quantity'] ) . '</strong>', $cart_item, $cart_item_key ) !!}
									{!! wc_get_formatted_cart_item_data( $cart_item ) !!}
								</div>
							</div>
						</div>
						<div class="flex-1 pt-2 flex items-end justify-between">
							<div class="mt-1 text-sm font-medium text-gray-900">
								{!! apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ) !!}
							</div>
						</div>
					</div>
				</li>
			@endif
		@endforeach
		@php
			do_action( 'woocommerce_review_order_after_cart_contents' );
		@endphp
	</ul>
	@php
		do_action( 'woocommerce_review_order_before_totals' );
	@endphp
	<dl class="border-t border-gray-200 py-6 px-4 space-y-6 sm:px-6">

		<div class="cart-subtotal flex items-center justify-between">
			<dt class="text-sm">{{ __( 'Subtotal', 'woocommerce' ) }}</dt>
			<dd class="text-sm font-medium text-gray-900">@php wc_cart_totals_subtotal_html(); @endphp</dd>
		</div>
		
		@foreach ( WC()->cart->get_coupons() as $code => $coupon )
			<div class="cart-discount coupon-{!! esc_attr( sanitize_title( $code ) ) !!} flex items-center justify-between">
				<dt class="text-sm"><span class="ml-2 rounded-full bg-gray-200 text-xs text-gray-600 py-0.5 px-2 tracking-wide">@php wc_cart_totals_coupon_label( $coupon ); @endphp</span></dt>
				<dd class="text-sm font-medium text-gray-900">@php wc_cart_totals_coupon_html( $coupon ); @endphp</dd>
			</div>
		@endforeach

		@foreach ( WC()->cart->get_fees() as $fee )
			<div class="fee flex items-center justify-between">
				<dt class="text-sm">{{ $fee->name }}</dt>
				<dd class="text-sm font-medium text-gray-900">@php wc_cart_totals_fee_html( $fee ); @endphp</dd>
			</div>
		@endforeach
		
		@if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() )
			@if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) )
				@foreach ( WC()->cart->get_tax_totals() as $code => $tax )
					<div class="tax-rate tax-rate-{!! esc_attr( sanitize_title( $code ) ) !!} flex items-center justify-between">
						<dt class="text-sm">{{ $tax->label }}</dt>
						<dd class="text-sm font-medium text-gray-900">{!! wp_kses_post( $tax->formatted_amount ) !!}</dd>
					</div>
				@endforeach
			@else
				<div class="tax-total flex items-center justify-between">
					<dt class="text-sm">{{ WC()->countries->tax_or_vat() }}}</dt>
					<dd class="text-sm font-medium text-gray-900">{!! wc_cart_totals_taxes_total_html() !!}</dd>
				</div>
			@endif
		@endif

		@php do_action( 'woocommerce_review_order_before_order_total' ); @endphp

		<div class="order-total flex items-center justify-between border-t border-gray-200 pt-6">
			<dt class="text-base font-medium">{{ __( 'Total', 'woocommerce' ) }}</dt>
			<dd class="text-base font-medium text-gray-900">{!! wc_cart_totals_order_total_html() !!}</dd>
		</div>

		@php do_action( 'woocommerce_review_order_after_order_total' ); @endphp

	</dl>
	@php
		do_action( 'woocommerce_review_order_after_totals' );
	@endphp
</div>
