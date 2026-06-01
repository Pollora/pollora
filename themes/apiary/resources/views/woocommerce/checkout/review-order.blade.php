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


@endphp
<div class="shop_table woocommerce-checkout-review-order-table">
	<ul class="divide-y divide-outline" role="list">
		@php
			do_action( 'woocommerce_review_order_before_cart_contents' );
        @endphp
		@foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item )
			@php
				$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			@endphp

			@if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) )
				<li class="flex items-center gap-4 py-3 {!! esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ) !!}">
					<div class="relative shrink-0 size-14 rounded-lg bg-surface-alt [&_img]:size-full [&_img]:object-cover [&_img]:m-0">
						{!! apply_filters( 'woocommerce_checkout_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key ) !!}
						@if ( $cart_item['quantity'] > 1 )
							<span class="absolute -top-1 -right-1 flex items-center justify-center size-5 text-[10px] font-bold text-white bg-primary rounded-full ring-2 ring-white">{{ $cart_item['quantity'] }}</span>
						@endif
					</div>
					@php
						$is_on_sale = $_product->is_on_sale();
						$regular_price = (float) $_product->get_regular_price();
						$sale_price = (float) $_product->get_price();
						$qty = $cart_item['quantity'];
						$savings = $is_on_sale ? ( $regular_price - $sale_price ) * $qty : 0;
					@endphp
					<div class="flex-1 min-w-0">
						<div class="text-sm font-medium text-foreground truncate">
							{!! wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ) !!}
						</div>
						<div class="text-xs text-muted">
							@if ( $is_on_sale )
								<del class="text-subtle">{!! wc_price( $regular_price ) !!}</del>
							@endif
							{!! wc_price( $sale_price ) !!}
						</div>
						@if ( $_product->get_short_description() )
							<div class="text-[11px] text-muted mt-0.5 line-clamp-2">{!! wp_kses_post( wp_strip_all_tags( $_product->get_short_description() ) ) !!}</div>
						@endif
						@if ( ! empty( $cart_item['variation'] ) )
							@php
								$attr_parts = [];
								foreach ( $cart_item['variation'] as $attr_key => $attr_value ) {
									if ( $attr_value ) {
										$taxonomy = str_replace( 'attribute_', '', $attr_key );
										$label = wc_attribute_label( $taxonomy, $_product );
										$term = taxonomy_exists( $taxonomy ) ? get_term_by( 'slug', $attr_value, $taxonomy ) : null;
										$value = $term ? $term->name : ucfirst( $attr_value );
										$attr_parts[] = $label . ': ' . $value;
									}
								}
							@endphp
							@if ( ! empty( $attr_parts ) )
								<div class="text-[11px] text-subtle mt-0.5">{{ implode( ' / ', $attr_parts ) }}</div>
							@endif
						@elseif ( wc_get_formatted_cart_item_data( $cart_item ) )
							<div class="text-[11px] text-subtle mt-0.5">{!! wp_strip_all_tags( wc_get_formatted_cart_item_data( $cart_item ) ) !!}</div>
						@endif
					</div>
					<div class="text-right shrink-0">
						<div class="text-sm font-semibold text-foreground">
							{!! apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $qty ), $cart_item, $cart_item_key ) !!}
						</div>
						@if ( $is_on_sale && $savings > 0 )
							<span class="inline-block mt-1 rounded-full bg-accent px-2 py-0.5 text-[11px] font-semibold text-foreground">
								{{ sprintf( __( 'Save %s', 'woocommerce' ), html_entity_decode( wp_strip_all_tags( wc_price( $savings ) ) ) ) }}
							</span>
						@endif
					</div>
				</li>
			@endif
		@endforeach
		@php
			do_action( 'woocommerce_review_order_after_cart_contents' );
		@endphp
	</ul>
	{{-- Coupon — collapsible panel (edge-to-edge borders via negative margins) --}}
	@if ( wc_coupons_enabled() )
		<div class="-mx-5 sm:-mx-6 border-t border-outline" x-data="{ couponOpen: false }">
			<button type="button" @click="couponOpen = !couponOpen" class="flex items-center justify-between w-full px-5 sm:px-6 py-3 text-sm font-medium text-foreground hover:text-primary transition-colors">
				<span>{{ __( 'Add coupons', 'woocommerce' ) }}</span>
				<svg class="size-5 text-muted transition-transform duration-200" :class="{ 'rotate-180': couponOpen }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
			</button>
			<div x-show="couponOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1" x-cloak class="px-5 sm:px-6 pb-4">
				<div class="js-coupon-panel flex gap-2">
					<div class="relative flex-1">
						<input type="text" data-coupon-code id="review_coupon_code" class="peer w-full rounded-lg border border-outline px-3 pt-5 pb-2 text-sm text-foreground placeholder-transparent focus:border-ring focus:ring-2 focus:ring-ring/20 focus:outline-hidden" placeholder="{{ esc_attr__( 'Enter code', 'woocommerce' ) }}" />
						<label for="review_coupon_code" class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/4 text-sm text-subtle transition-all peer-placeholder-shown:top-2/5 peer-placeholder-shown:text-sm peer-focus:top-2 peer-focus:text-xs peer-focus:text-muted peer-[:not(:placeholder-shown)]:top-2 peer-[:not(:placeholder-shown)]:text-xs peer-[:not(:placeholder-shown)]:text-muted">{{ __( 'Enter code', 'woocommerce' ) }}</label>
					</div>
					<button type="button" data-apply-coupon class="shrink-0 rounded-lg bg-primary px-5 py-2 text-sm font-semibold text-white hover:bg-primary-hover transition-colors">{{ __( 'Apply', 'woocommerce' ) }}</button>
				</div>
			</div>
		</div>
	@endif

	@php
		do_action( 'woocommerce_review_order_before_totals' );
	@endphp
	<dl class="-mx-5 sm:-mx-6 border-t border-outline px-5 sm:px-6 py-4 space-y-3">

		<div class="cart-subtotal flex items-center justify-between">
			<dt class="text-sm text-muted">{{ __( 'Subtotal', 'woocommerce' ) }}</dt>
			<dd class="text-sm font-medium text-foreground">@php wc_cart_totals_subtotal_html(); @endphp</dd>
		</div>

		@if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() )
			@php do_action( 'woocommerce_review_order_before_shipping' ); @endphp
			@foreach ( WC()->session->get( 'shipping_for_package_0', [] )['rates'] ?? [] as $method )
				@if ( $method->id === WC()->session->get( 'chosen_shipping_methods', [] )[0] ?? '' )
					<div class="shipping flex items-center justify-between">
						<dt class="text-sm text-muted">{!! esc_html( $method->get_label() ) !!}</dt>
						<dd class="text-sm font-medium text-foreground">
							@if ( (float) $method->cost === 0.0 )
								{{ __( 'FREE', 'woocommerce' ) }}
							@else
								{!! wc_price( WC()->cart->display_prices_including_tax() ? $method->cost + $method->get_shipping_tax() : $method->cost ) !!}
							@endif
						</dd>
					</div>
				@endif
			@endforeach
			@php do_action( 'woocommerce_review_order_after_shipping' ); @endphp
		@endif

		{{-- Coupons — Gutenberg-style: "Discount" line + badge row --}}
		@if ( WC()->cart->get_coupons() )
			@php
				$total_discount = 0;
				foreach ( WC()->cart->get_coupons() as $coupon ) {
					$total_discount += WC()->cart->get_coupon_discount_amount( $coupon->get_code(), WC()->cart->display_cart_ex_tax );
				}
			@endphp
			<div class="cart-discount">
				<div class="flex items-center justify-between">
					<dt class="text-sm text-muted">{{ __( 'Discount', 'woocommerce' ) }}</dt>
					<dd class="text-sm font-medium text-success">-{!! wc_price( $total_discount ) !!}</dd>
				</div>
				<div class="flex flex-wrap gap-1.5 mt-2">
					@foreach ( WC()->cart->get_coupons() as $code => $coupon )
						<span class="inline-flex items-center gap-1 rounded-full border border-outline bg-white px-2.5 py-0.5 text-xs text-muted">
							{{ esc_html( $code ) }}
							<a href="{!! esc_url( add_query_arg( 'remove_coupon', rawurlencode( $coupon->get_code() ), wc_get_checkout_url() ) ) !!}"
							   class="woocommerce-remove-coupon text-subtle hover:text-error transition-colors"
							   data-coupon="{!! esc_attr( $coupon->get_code() ) !!}"
							   aria-label="{{ esc_attr( sprintf( __( 'Remove coupon %s', 'woocommerce' ), $code ) ) }}">
								<svg class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
							</a>
						</span>
					@endforeach
				</div>
			</div>
		@endif

		@foreach ( WC()->cart->get_fees() as $fee )
			<div class="fee flex items-center justify-between">
				<dt class="text-sm text-muted">{{ $fee->name }}</dt>
				<dd class="text-sm font-medium text-foreground">@php wc_cart_totals_fee_html( $fee ); @endphp</dd>
			</div>
		@endforeach

		@if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() )
			@if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) )
				@foreach ( WC()->cart->get_tax_totals() as $code => $tax )
					<div class="tax-rate tax-rate-{!! esc_attr( sanitize_title( $code ) ) !!} flex items-center justify-between">
						<dt class="text-sm text-muted">{{ $tax->label }}</dt>
						<dd class="text-sm font-medium text-foreground">{!! wp_kses_post( $tax->formatted_amount ) !!}</dd>
					</div>
				@endforeach
			@else
				<div class="tax-total flex items-center justify-between">
					<dt class="text-sm text-muted">{{ WC()->countries->tax_or_vat() }}</dt>
					<dd class="text-sm font-medium text-foreground">{!! wc_cart_totals_taxes_total_html() !!}</dd>
				</div>
			@endif
		@endif

		@php do_action( 'woocommerce_review_order_before_order_total' ); @endphp

	</dl>
	<dl class="-mx-5 sm:-mx-6 border-t border-outline px-5 sm:px-6 pt-4 pb-1">
		<div class="order-total flex items-center justify-between">
			<dt class="text-base font-bold">{{ __( 'Total', 'woocommerce' ) }}</dt>
			<dd class="text-base font-bold text-foreground">{!! wc_cart_totals_order_total_html() !!}</dd>
		</div>

		@php do_action( 'woocommerce_review_order_after_order_total' ); @endphp
	</dl>
	@php
		do_action( 'woocommerce_review_order_after_totals' );
	@endphp
</div>
