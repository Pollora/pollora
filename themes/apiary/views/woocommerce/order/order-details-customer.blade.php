{{-- *
 * Order Customer Details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details-customer.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.6.0
  --}}
{{-- docs.woocommerce.com/document/template-structure/ --}}
@php
	$show_shipping = ! wc_ship_to_billing_address_only() && $order->needs_shipping_address();
@endphp
<div class="woocommerce--billing-address">
	<dt class="woocommerce-column__title font-medium text-gray-900">@php esc_html_e( 'Billing address', 'woocommerce' ); @endphp</dt>
	<dd class="mt-2 text-gray-700">
		<address class="not-italic">
			{!! wp_kses_post( $order->get_formatted_billing_address( esc_html__( 'N/A', 'woocommerce' ) ) ) !!}
			@if ( $order->get_billing_phone() )
				<p class="woocommerce-customer-details--phone">{!! esc_html( $order->get_billing_phone() ) !!}</p>
			@endif

			@if ( $order->get_billing_email() )
				<p class="woocommerce-customer-details--email">{!! esc_html( $order->get_billing_email() ) !!}</p>
			@endif
		</address>
	</dd>
</div>
@if ( $show_shipping )
	<div class="woocommerce--shipping-address">
		<dt class="woocommerce-column__title font-medium text-gray-900">@php esc_html_e( 'Shipping address', 'woocommerce' ); @endphp</dt>
		<dd class="mt-2 text-gray-700">
			<address class="not-italic">
				{!! wp_kses_post( $order->get_formatted_shipping_address( esc_html__( 'N/A', 'woocommerce' ) ) ) !!}

				@if ( $order->get_shipping_phone() )
					<p class="woocommerce-customer-details--phone">{!! esc_html( $order->get_shipping_phone() ) !!}</p>
				@endif
			</address>
		</dd>
	</div>
@endif
@php do_action( 'woocommerce_order_details_after_customer_details', $order ); @endphp
