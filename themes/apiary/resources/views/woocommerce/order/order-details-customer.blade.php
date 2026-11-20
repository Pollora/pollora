{{--
 * Order Customer Details
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.7.0
 --}}
@php
    $show_shipping = ! wc_ship_to_billing_address_only() && $order->needs_shipping_address();
@endphp

<div class="woocommerce--billing-address rounded-xl border border-outline bg-white p-5">
    <dt class="!mt-0 text-xs font-semibold text-muted uppercase tracking-wider">@php esc_html_e( 'Billing address', 'woocommerce' ); @endphp</dt>
    <dd class="!pl-0 mt-3 text-sm text-foreground">
        <address class="not-italic leading-relaxed">
            {!! wp_kses_post( $order->get_formatted_billing_address( esc_html__( 'N/A', 'woocommerce' ) ) ) !!}
            @if ( $order->get_billing_phone() )
                <p class="woocommerce-customer-details--phone mt-2 text-muted">{!! esc_html( $order->get_billing_phone() ) !!}</p>
            @endif
            @if ( $order->get_billing_email() )
                <p class="woocommerce-customer-details--email text-muted">{!! esc_html( $order->get_billing_email() ) !!}</p>
            @endif
        </address>
        @php do_action( 'woocommerce_order_details_after_customer_address', 'billing', $order ); @endphp
    </dd>
</div>

@if ( $show_shipping )
    <div class="woocommerce--shipping-address rounded-xl border border-outline bg-white p-5">
        <dt class="!mt-0 text-xs font-semibold text-muted uppercase tracking-wider">@php esc_html_e( 'Shipping address', 'woocommerce' ); @endphp</dt>
        <dd class="!pl-0 mt-3 text-sm text-foreground">
            <address class="not-italic leading-relaxed">
                {!! wp_kses_post( $order->get_formatted_shipping_address( esc_html__( 'N/A', 'woocommerce' ) ) ) !!}
                @if ( $order->get_shipping_phone() )
                    <p class="woocommerce-customer-details--phone mt-2 text-muted">{!! esc_html( $order->get_shipping_phone() ) !!}</p>
                @endif
            </address>
            @php do_action( 'woocommerce_order_details_after_customer_address', 'shipping', $order ); @endphp
        </dd>
    </div>
@endif

@php do_action( 'woocommerce_order_details_after_customer_details', $order ); @endphp
