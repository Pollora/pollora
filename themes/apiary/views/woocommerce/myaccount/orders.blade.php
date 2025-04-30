{{-- *
 * Orders
 *
 * Shows orders on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/orders.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.7.0
  --}}
{{-- docs.woocommerce.com/document/template-structure/ --}}
@php
	do_action( 'woocommerce_before_account_orders', $has_orders );
@endphp
@if ( $has_orders )
	<div class="shadow-sm overflow-hidden border-b border-gray-200 sm:rounded-lg">
		<table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table min-w-full divide-y divide-gray-200">
			<thead class="bg-gray-50">
				<tr>
					@foreach ( wc_get_account_orders_columns() as $column_id => $column_name )
						<th scope="col" class="woocommerce-orders-table__header woocommerce-orders-table__header-{!! esc_attr( $column_id ) !!} px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><span class="nobr">{!! esc_html( $column_name ) !!}</span></th>
					@endforeach
				</tr>
			</thead>
			<tbody class="bg-white divide-y divide-gray-200">
				@foreach ( $customer_orders->orders as $customer_order )
					@php
						$order = wc_get_order( $customer_order );
						$item_count = $order->get_item_count() - $order->get_item_count_refunded();
					@endphp
					<tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-{!! esc_attr( $order->get_status() ) !!} order">
						@foreach ( wc_get_account_orders_columns() as $column_id => $column_name )
							<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-{!! esc_attr( $column_id ) !!} px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" data-title="{!! esc_attr( $column_name ) !!}">
								@if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) )
									@php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); @endphp
								@elseif ( 'order-number' === $column_id )
									<a href="{!! esc_url( $order->get_view_order_url() ) !!}">
										{!! esc_html( _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number() ) !!}
									</a>
								@elseif ( 'order-date' === $column_id )
									<time datetime="{!! esc_attr( $order->get_date_created()->date( 'c' ) ) !!}">{!! esc_html( wc_format_datetime( $order->get_date_created() ) ) !!}</time>
								@elseif ( 'order-status' === $column_id )
									{!! esc_html( wc_get_order_status_name( $order->get_status() ) ) !!}
								@elseif ( 'order-total' === $column_id )
									{{--  translators: 1: formatted order total 2: total order items  --}}
									{!! wp_kses_post( sprintf( _n( '%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count ) ) !!}
								@elseif ( 'order-actions' === $column_id )
									{{--  phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited --}}
									@php
										$actions = wc_get_account_orders_actions( $order )
									@endphp
									@if ( ! empty( $actions ) )
										@foreach ( $actions as $key => $action )
											<a href="{{ esc_url( $action['url'] ) }}" class="text-indigo-600 hover:text-indigo-900 {{ sanitize_html_class( $key ) }}">
												{{ $action['name'] }}
											</a>
										@endforeach
									@endif
								@endif
							</td>
						@endforeach
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	@php do_action( 'woocommerce_before_account_orders_pagination' ); @endphp

	
	@if ( 1 < $customer_orders->max_num_pages )
		<nav class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
			<div class="flex-1 flex justify-between sm:justify-end">
				@if ( 1 !== $current_page )
					<a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50" href="{!! esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ) !!}">
						@php esc_html_e( 'Previous', 'woocommerce' ); @endphp
					</a>
				@endif
				@if ( intval( $customer_orders->max_num_pages ) !== $current_page )
					<a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50" href="{!! esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ) !!}">
						@php esc_html_e( 'Next', 'woocommerce' ); @endphp
					</a>
				@endif
			</div>
		</nav>
	@endif

@else
	<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info lg:flex">
		<div class="py-4 lg:py-2 italic">
			@php esc_html_e( 'No order has been made yet.', 'woocommerce' ); @endphp
		</div>
		<div class="ml-auto lg:pl-4">
			<a class="woocommerce-Button button w-full flex items-center justify-center bg-indigo-600 py-2 px-2.5 border border-transparent rounded-md shadow-xs text-sm font-medium text-white hover:bg-indigo-700 focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:w-full sm:grow-0" href="{!! esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ) !!}">
				@php esc_html_e( 'Browse products', 'woocommerce' ); @endphp
			</a>
		</div>
	</div>
@endif


@php do_action( 'woocommerce_after_account_orders', $has_orders ); @endphp
