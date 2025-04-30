{{-- *
 * My Orders - Deprecated
 *
 * @deprecated 2.6.0 this template file is no longer used. My Account shortcode uses orders.php.
 * @package WooCommerce\Templates
  --}}
@php


defined( 'ABSPATH' ) || exit;

$my_orders_columns = apply_filters(
	'woocommerce_my_account_my_orders_columns',
	array(
		'order-number'  => esc_html__( 'Order', 'woocommerce' ),
		'order-date'    => esc_html__( 'Date', 'woocommerce' ),
		'order-status'  => esc_html__( 'Status', 'woocommerce' ),
		'order-total'   => esc_html__( 'Total', 'woocommerce' ),
		'order-actions' => '&nbsp;',
	)
);

$customer_orders = get_posts(
	apply_filters(
		'woocommerce_my_account_my_orders_query',
		array(
			'numberposts' => $order_count,
			'meta_key'    => '_customer_user',
			'meta_value'  => get_current_user_id(),
			'post_type'   => wc_get_order_types( 'view-orders' ),
			'post_status' => array_keys( wc_get_order_statuses() ),
		)
	)
);
@endphp
@if ( $customer_orders )


	<h2>{{--  phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped  --}}
{!! apply_filters( 'woocommerce_my_account_my_orders_title', esc_html__( 'Recent orders', 'woocommerce' ) ) !!}</h2>

	<table class="shop_table shop_table_responsive my_account_orders">

		<thead>
			<tr>
				
@foreach ( $my_orders_columns as $column_id => $column_name )

					<th class="{!! esc_attr( $column_id ) !!}"><span class="nobr">{!! esc_html( $column_name ) !!}</span></th>
				
@endforeach

			</tr>
		</thead>

		<tbody>
			{{--  phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited --}}

@foreach ( $customer_orders as $customer_order )
@php
				$order      = wc_get_order( $customer_order ); 				$item_count = $order->get_item_count();
				@endphp
				<tr class="order">
					
@foreach ( $my_orders_columns as $column_id => $column_name )

						<td class="{!! esc_attr( $column_id ) !!}" data-title="{!! esc_attr( $column_name ) !!}">
							
@if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) )

								@php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); @endphp

							
@elseif ( 'order-number' === $column_id )

								<a href="{!! esc_url( $order->get_view_order_url() ) !!}">
									{{--  phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped  --}}
{!! _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number() !!}
								</a>

							
@elseif ( 'order-date' === $column_id )

								<time datetime="{!! esc_attr( $order->get_date_created()->date( 'c' ) ) !!}">{!! esc_html( wc_format_datetime( $order->get_date_created() ) ) !!}</time>

							
@elseif ( 'order-status' === $column_id )

								{!! esc_html( wc_get_order_status_name( $order->get_status() ) ) !!}

							
@elseif ( 'order-total' === $column_id )

								{{--  translators: 1: formatted order total 2: total order items  --}}
{{--  phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped --}}
@php
								
								printf( _n( '%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count ); 								@endphp

							
@elseif ( 'order-actions' === $column_id )

								{{--  phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited --}}
@php
								$actions = wc_get_account_orders_actions( $order );

								if ( ! empty( $actions ) ) {
									foreach ( $actions as $key => $action ) { 										echo '<a href="' . esc_url( $action['url'] ) . '" class="button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
									}
								}
								@endphp
							
@endif

						</td>
					
@endforeach

				</tr>
			
@endforeach

		</tbody>
	</table>

@endif

