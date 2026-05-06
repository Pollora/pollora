{{-- *
 * Order Item Details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details-item.php.
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
	if ( ! apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
		return;
	}
@endphp
<div class="{!! esc_attr( apply_filters( 'woocommerce_order_item_class', 'woocommerce-table__line-item order_item py-10 border-b border-gray-200 flex space-x-6', $item, $order ) ) !!}">
	@php
		$is_visible        = $product && $product->is_visible();
		$product_permalink = apply_filters( 'woocommerce_order_item_permalink', $is_visible ? $product->get_permalink( $item ) : '', $item, $order );
		$qty          = $item->get_quantity();
		$refunded_qty = $order->get_qty_refunded_for_item( $item_id );
		if ( $refunded_qty ) {
			$qty_display = '<del>' . esc_html( $qty ) . '</del> <ins>' . esc_html( $qty - ( $refunded_qty * -1 ) ) . '</ins>';
		} else {
			$qty_display = esc_html( $qty );
		}
	@endphp
	{!! apply_filters( 'woocommerce_thank_you_item_thumbnail', $product->get_image(), $item ) !!}
	<div class="flex-auto flex flex-col">
		<div>
			<h4 class="font-medium text-gray-900">
				{!! wp_kses_post( apply_filters( 'woocommerce_order_item_name', $product_permalink ? sprintf( '<a href="%s">%s</a>', $product_permalink, $item->get_name() ) : $item->get_name(), $item, $is_visible ) ); !!}
			</h4>
			<p class="mt-2 text-sm text-gray-600">
				{{ $product->get_short_description() }}
			</p>
			@php
				do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order, false );
				wc_display_item_meta( $item, [
					'before' => '<div class="variation mt-1 flex text-sm text-gray-500">',
					'after' => '</div>',
					'separator' => ', ',
					'label_before' => '',
					'label_after' => ':&nbsp;',
				]);
				do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order, false );
			@endphp
		</div>
		<div class="mt-6 flex-1 flex items-end">
			<dl class="flex text-sm divide-x divide-gray-200 space-x-4 sm:space-x-6">
				<div class="flex">
					<dt class="font-medium text-gray-900">{{ __('Quantity', 'woocommerce') }}</dt>
					<dd class="ml-2 text-gray-700">
						{!! apply_filters( 'woocommerce_order_item_quantity_html', ' <strong class="product-quantity">' . sprintf( '&times;&nbsp;%s', $qty_display ) . '</strong>', $item ) !!}
					</dd>
				</div>
				<div class="pl-4 flex sm:pl-6">
					<dt class="font-medium text-gray-900">{{ __('Price', 'woocommerce') }}</dt>
					<dd class="woocommerce-table__product-total product-total ml-2 text-gray-700">
						{!! $order->get_formatted_line_subtotal( $item ) !!}
					</dd>
				</div>
			</dl>
		</div>
	</div>
</div>


@if ( $show_purchase_note && $purchase_note )


<tr class="woocommerce-table__product-purchase-note product-purchase-note">

	<td colspan="2">{{--  phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped  --}}
{!! wpautop( do_shortcode( wp_kses_post( $purchase_note ) ) ) !!}</td>

</tr>


@endif

