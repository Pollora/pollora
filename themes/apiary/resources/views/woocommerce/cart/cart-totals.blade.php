{{--
 * Cart totals
 *
 * Aligned on the Gutenberg Cart block sidebar: collapsible coupon panel,
 * inline shipping row, clean totals layout.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.3.6
 --}}

<div class="cart_totals {!! WC()->customer->has_calculated_shipping() ? 'calculated_shipping' : '' !!}">
    @php do_action( 'woocommerce_before_cart_totals' ); @endphp

    <h2 class="text-sm font-semibold text-foreground">{{ __( 'Cart totals', 'woocommerce' ) }}</h2>

    {{-- Coupon — collapsible panel (matches checkout review-order pattern) --}}
    @if ( wc_coupons_enabled() )
        <div class="mt-3 border-t border-outline" x-data="{ couponOpen: false }">
            <button type="button" @click="couponOpen = !couponOpen" class="flex items-center justify-between w-full py-3 text-sm font-medium text-foreground hover:text-primary transition-colors">
                <span>{{ __( 'Add coupons', 'woocommerce' ) }}</span>
                <svg class="size-5 text-muted transition-transform duration-200" :class="{ 'rotate-180': couponOpen }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
            </button>
            <div x-show="couponOpen" x-transition x-cloak class="pb-4">
                <div class="js-cart-coupon-panel flex gap-2">
                    <input type="text" data-coupon-code id="cart_coupon_code"
                           class="flex-1 rounded-lg border border-outline px-3 py-2.5 text-sm text-foreground placeholder:text-subtle focus:border-ring focus:ring-2 focus:ring-ring/20 focus:outline-hidden"
                           placeholder="{{ esc_attr__( 'Enter code', 'woocommerce' ) }}" />
                    <button type="button" data-apply-coupon
                            class="shrink-0 rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white hover:bg-primary-hover transition-colors">
                        {{ __( 'Apply', 'woocommerce' ) }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    <dl class="shop_table space-y-3 border-t border-outline pt-3">
        {{-- Subtotal --}}
        <div class="cart-subtotal flex items-center justify-between">
            <dt class="text-sm text-muted">{{ __( 'Subtotal', 'woocommerce' ) }}</dt>
            <dd class="text-sm font-medium text-foreground">@php wc_cart_totals_subtotal_html(); @endphp</dd>
        </div>

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
                            <a href="{!! esc_url( add_query_arg( 'remove_coupon', rawurlencode( $coupon->get_code() ), defined( 'WOOCOMMERCE_CHECKOUT' ) ? wc_get_checkout_url() : wc_get_cart_url() ) ) !!}"
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

        {{-- Shipping — inline row matching Gutenberg style --}}
        @if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() )
            @php
                do_action( 'woocommerce_cart_totals_before_shipping' );
                $packages = WC()->shipping()->get_packages();
                $chosen_methods = WC()->session->get( 'chosen_shipping_methods', [] );
            @endphp
            @foreach ( $packages as $i => $package )
                @php $chosen = $chosen_methods[ $i ] ?? ''; @endphp
                @foreach ( $package['rates'] as $method )
                    @if ( $method->id === $chosen )
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
            @endforeach
            @php do_action( 'woocommerce_cart_totals_after_shipping' ); @endphp
        @elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) )
            <div class="shipping flex items-center justify-between">
                <dt class="text-sm text-muted">{{ __( 'Shipping', 'woocommerce' ) }}</dt>
                <dd class="text-sm font-medium text-foreground">
                    {!! woocommerce_shipping_calculator() !!}
                </dd>
            </div>
        @endif

        {{-- Fees --}}
        @foreach ( WC()->cart->get_fees() as $fee )
            <div class="fee flex items-center justify-between">
                <dt class="text-sm text-muted">{!! esc_html( $fee->name ) !!}</dt>
                <dd class="text-sm font-medium text-foreground">@php wc_cart_totals_fee_html( $fee ); @endphp</dd>
            </div>
        @endforeach

        {{-- Taxes --}}
        @if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() )
            @php
                $taxable_address = WC()->customer->get_taxable_address();
                $estimated_text  = '';
                if ( WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ) {
                    $estimated_text = sprintf( ' <small>' . esc_html__( '(estimated for %s)', 'woocommerce' ) . '</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] );
                }
            @endphp
            @if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) )
                @foreach ( WC()->cart->get_tax_totals() as $code => $tax )
                    <div class="tax-rate flex items-center justify-between tax-rate-{!! esc_attr( sanitize_title( $code ) ) !!}">
                        <dt class="text-sm text-muted">{!! $tax->label . $estimated_text !!}</dt>
                        <dd class="text-sm font-medium text-foreground">{!! wp_kses_post( $tax->formatted_amount ) !!}</dd>
                    </div>
                @endforeach
            @else
                <div class="tax-total flex items-center justify-between">
                    <dt class="text-sm text-muted">{!! WC()->countries->tax_or_vat() . $estimated_text !!}</dt>
                    <dd class="text-sm font-medium text-foreground">{!! wc_cart_totals_taxes_total_html() !!}</dd>
                </div>
            @endif
        @endif

        @php do_action( 'woocommerce_cart_totals_before_order_total' ); @endphp
    </dl>

    {{-- Total --}}
    <dl class="border-t border-outline mt-4 pt-4">
        <div class="order-total flex items-center justify-between">
            <dt class="text-base font-bold text-foreground">{{ __( 'Estimated total', 'woocommerce' ) }}</dt>
            <dd class="text-base font-bold text-foreground">@php wc_cart_totals_order_total_html(); @endphp</dd>
        </div>
        @php do_action( 'woocommerce_cart_totals_after_order_total' ); @endphp
    </dl>

    {{-- Proceed to checkout --}}
    <div class="wc-proceed-to-checkout mt-6">
        @php do_action( 'woocommerce_proceed_to_checkout' ); @endphp
    </div>

    @php do_action( 'woocommerce_after_cart_totals' ); @endphp
</div>
