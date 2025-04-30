{{-- *
 * Cart totals
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-totals.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.3.6
  --}}
{{-- docs.woocommerce.com/document/template-structure/ --}}
<div class="cart_totals {!! ( WC()->customer->has_calculated_shipping() ) ? 'calculated_shipping' : '' !!}">

    @php do_action( 'woocommerce_before_cart_totals' ); @endphp
    <h2 id="summary-heading" class="text-lg font-medium text-gray-900">@php esc_html_e( 'Cart totals', 'woocommerce' ); @endphp</h2>

    <dl class="shop_table mt-6 space-y-4">
        <div class="cart-subtotal flex items-center justify-between">
            <dt class="text-sm text-gray-600">@php esc_html_e( 'Subtotal', 'woocommerce' ); @endphp</dt>
            <dd class="text-sm font-medium text-gray-900">@php wc_cart_totals_subtotal_html(); @endphp</dd>
        </div>
        @foreach ( WC()->cart->get_coupons() as $code => $coupon )
            <div class="cart-discount border-t border-gray-200 pt-4 flex items-center justify-between coupon-{!! esc_attr( sanitize_title( $code ) ) !!}">
                <dt class="text-sm text-gray-600">@php wc_cart_totals_coupon_label( $coupon ); @endphp</dt>
                <dd class="text-sm font-medium text-gray-900" data-title="{!! esc_attr( wc_cart_totals_coupon_label( $coupon, false ) ) !!}">@php wc_cart_totals_coupon_html( $coupon ); @endphp</dd>
            </div>
        @endforeach

        @if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() )
            @php do_action( 'woocommerce_cart_totals_before_shipping' ); @endphp
            @php wc_cart_totals_shipping_html(); @endphp
            @php do_action( 'woocommerce_cart_totals_after_shipping' ); @endphp
        @elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) )
            <div class="shipping border-t border-gray-200 pt-4 flex items-center justify-between">
                <dt class="text-sm text-gray-600">@php esc_html_e( 'Shipping', 'woocommerce' ); @endphp</dt>
                <dd class="text-sm font-medium text-gray-900">
                    {!! woocommerce_shipping_calculator() !!}
                </dd>
            </div>
        @endif

        @foreach ( WC()->cart->get_fees() as $fee )
            <div class="fee border-t border-gray-200 pt-4 flex items-center justify-between">
                <dt class="text-sm text-gray-600">{!! esc_html( $fee->name ) !!}</dt>
                <dd class="text-sm font-medium text-gray-900" data-title="{!! esc_attr( $fee->name ) !!}">@php wc_cart_totals_fee_html( $fee ); @endphp</dd>
            </div>
        @endforeach

        {{--  translators: %s location.  --}}
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
                    <div class="tax-rate border-t border-gray-200 pt-4 flex items-center justify-between tax-rate-{!! esc_attr( sanitize_title( $code ) ) !!}">
                        <dt class="text-sm text-gray-600">
                            {!! ($tax->label . $estimated_text) !!}
                        </dt>
                        <dd class="text-sm font-medium text-gray-900" data-title="{!! esc_attr( $tax->label ) !!}">
                            {!! wp_kses_post( $tax->formatted_amount ) !!}
                        </dd>
                    </div>
                @endforeach
            @else
                <div class="tax-total border-t border-gray-200 pt-4 flex items-center justify-between">
                    <dt class="text-sm text-gray-600">
                        {!! (WC()->countries->tax_or_vat() . $estimated_text) !!}
                    </dt>
                    <dd class="text-sm font-medium text-gray-900" data-title="{!! esc_attr( WC()->countries->tax_or_vat() ) !!}">
                        {!! wc_cart_totals_taxes_total_html() !!}
                    </dd>
                </div>
            @endif
        @endif

        @php do_action( 'woocommerce_cart_totals_before_order_total' ); @endphp

        <div class="order-total border-t border-gray-200 pt-4 flex items-center justify-between">
            <dt class="text-base font-medium text-gray-900">
                @php esc_html_e( 'Total', 'woocommerce' ); @endphp
            </dt>
            <dd class="text-base font-medium text-gray-900" data-title="@php esc_attr_e( 'Total', 'woocommerce' ); @endphp">
                @php wc_cart_totals_order_total_html(); @endphp
            </dd>
        </div>

        @php do_action( 'woocommerce_cart_totals_after_order_total' ); @endphp

    </dl>

    <div class="wc-proceed-to-checkout mt-6">
        @php do_action( 'woocommerce_proceed_to_checkout' ); @endphp
    </div>

    @php do_action( 'woocommerce_after_cart_totals' ); @endphp

</div>
