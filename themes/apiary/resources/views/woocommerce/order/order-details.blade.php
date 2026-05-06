{{-- *
 * Order details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.6.0
  --}}
{{--  phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited --}}
{{-- docs.woocommerce.com/document/template-structure/ --}}
@php


defined( 'ABSPATH' ) || exit;

$order = wc_get_order( $order_id ); 
if ( ! $order ) {
	return;
}

$order_items           = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
$show_purchase_note    = $order->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );
$show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();
$downloads             = $order->get_downloadable_items();
$show_downloads        = $order->has_downloadable_item() && $order->is_download_permitted();

if ( $show_downloads ) {
	wc_get_template(
		'order/order-downloads.php',
		array(
			'downloads'  => $downloads,
			'show_title' => true,
		)
	);
}
@endphp

@if (!$order->has_status( 'failed' ))
	@php
		$statuses = wc_get_current_order_statuses($order);
	    $status_percent = wc_get_order_status_progress($order);

		// ugly but required for css purge
		switch (count($statuses)) {
			case 2:
				$grid_column_class = 'grid-cols-2';
				break;
			case 3:
				$grid_column_class = 'grid-cols-3';
				break;
			case 4:
				$grid_column_class = 'grid-cols-4';
				break;
			case 5:
				$grid_column_class = 'grid-cols-5';
				break;
			case 6:
				$grid_column_class = 'grid-cols-6';
				break;
			default:
				$grid_column_class = 'grid-cols-1';
		}
	@endphp


	<div class="mt-6">
		<div class="bg-gray-200 rounded-full overflow-hidden">
			<div class="h-2 bg-indigo-600 rounded-full" style="width: {{ $status_percent }}%"></div>
		</div>
		<div class="hidden sm:grid {{$grid_column_class }} font-medium text-gray-600 mt-6">
			@foreach($statuses as $status)
				<div class="text-indigo-600 @if (!$loop->first && ! $loop->last) text-center @elseif ($loop->last) text-right @endif">{{ $status }}</div>
			@endforeach
		</div>
	</div>
@endif

<section class="woocommerce-order-details mt-10 border-t border-gray-200">
	@php do_action( 'woocommerce_order_details_before_order_table', $order ); @endphp
	<h2 class="woocommerce-order-details__title font-medium text-gray-900 mt-10 mb-0">@php esc_html_e( 'Order details', 'woocommerce' ); @endphp</h2>

	<div class="woocommerce-table woocommerce-table--order-details shop_table order_details">
		<div>
			@php
				do_action( 'woocommerce_order_details_before_order_table_items', $order );

				foreach ( $order_items as $item_id => $item ) {
					$product = $item->get_product();

					wc_get_template(
						'order/order-details-item.php',
						array(
							'order'              => $order,
							'item_id'            => $item_id,
							'item'               => $item,
							'show_purchase_note' => $show_purchase_note,
							'purchase_note'      => $product ? $product->get_purchase_note() : '',
							'product'            => $product,
						)
					);
				}

				do_action( 'woocommerce_order_details_after_order_table_items', $order );
			@endphp
		</div>

		<div class="sm:ml-40 sm:pl-6">
			<dl class="grid grid-cols-2 gap-x-6 text-sm py-10">
				@if ( $show_customer_details )
					{!! wc_get_template( 'order/order-details-customer.php', array( 'order' => $order ) ) !!}
				@endif
			</dl>
			<dl class="grid grid-cols-2 gap-x-6 border-t border-gray-200 text-sm py-10">
				@foreach ( $order->get_order_item_totals() as $key => $total )
					@php
						if ( !in_array($key, ['payment_method', 'shipping']) ) {
							continue;
						}
					@endphp
					<div>
						<dt class="font-medium text-gray-900">{{ __( $total['label'] ) }}</dt>
						<dd class="mt-2 text-gray-700">
							{!! ( 'payment_method' === $key ) ? esc_html( $total['value'] ) : wp_kses_post( $total['value'] ) !!}
						</dd>
					</div>
				@endforeach
			</dl>

			<dl class="space-y-6 border-t border-gray-200 text-sm pt-10">
				@foreach ( $order->get_order_item_totals() as $key => $total )
					@php
						if ( in_array($key, ['payment_method', 'shipping']) ) {
							continue;
						}
					@endphp
					<div class="order-total flex justify-between">
						<dt class="font-medium text-gray-900">
							{{ __( $total['label'] ) }}
						</dt>
						<dd class="text-gray-700">
							{!! wp_kses_post( $total['value'] ) !!}
						</dd>
					</div>
				@endforeach
			</dl>
			
			@if ( $order->get_customer_note() )
				<div class="mt-10 bg-gray-50 rounded-lg py-6 px-6 text-sm">
					<div class="font-medium text-gray-900">@php esc_html_e( 'Note:', 'woocommerce' ); @endphp</div>
					<div class="text-gray-700">{!! wp_kses_post( nl2br( wptexturize( $order->get_customer_note() ) ) ) !!}</div>
				</div>
			@endif

		</div>
	</div>

	@php do_action( 'woocommerce_order_details_after_order_table', $order ); @endphp
</section>

{{-- *
 * Action hook fired after the order details.
 *
 * @since 4.4.0
 * @param WC_Order $order Order data.
  --}}
@php

do_action( 'woocommerce_after_order_details', $order );

@endphp