{{--
 * Order Item Details
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 --}}
@php
    if ( ! apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
        return;
    }

    $is_visible        = $product && $product->is_visible();
    $product_permalink = apply_filters( 'woocommerce_order_item_permalink', $is_visible ? $product->get_permalink( $item ) : '', $item, $order );
    $qty          = $item->get_quantity();
    $refunded_qty = $order->get_qty_refunded_for_item( $item_id );
    if ( $refunded_qty ) {
        $qty_display = '<del>' . esc_html( $qty ) . '</del> <ins>' . esc_html( $qty - ( $refunded_qty * -1 ) ) . '</ins>';
    } else {
        $qty_display = esc_html( $qty );
    }

    $thumbnail = $product ? $product->get_image( 'woocommerce_gallery_thumbnail' ) : '';
    $thumbnail = apply_filters( 'woocommerce_thank_you_item_thumbnail', $thumbnail, $item );
@endphp

<div class="{{ esc_attr( apply_filters( 'woocommerce_order_item_class', 'woocommerce-table__line-item order_item py-5 border-b border-outline last:border-b-0 flex gap-4', $item, $order ) ) }}">
    @if ($thumbnail)
        <div class="not-prose shrink-0 size-16 sm:size-[72px] rounded-lg overflow-hidden bg-surface-alt [&_img]:size-full [&_img]:object-cover [&_img]:m-0">
            {!! $thumbnail !!}
        </div>
    @endif
    <div class="flex-1 min-w-0 flex flex-col justify-between gap-2">
        <div>
            <h4 class="text-sm font-medium text-foreground leading-snug">
                {!! wp_kses_post( apply_filters( 'woocommerce_order_item_name', $product_permalink ? sprintf( '<a href="%s" class="hover:text-primary transition-colors">%s</a>', $product_permalink, $item->get_name() ) : $item->get_name(), $item, $is_visible ) ) !!}
            </h4>
            @php
                do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order, false );

                $formatted_meta = $item->get_formatted_meta_data();
                if ( $formatted_meta ) {
                    $meta_parts = [];
                    foreach ( $formatted_meta as $meta ) {
                        $value = wp_strip_all_tags( $meta->display_value );
                        $meta_parts[] = '<span class="font-medium">' . wp_kses_post( $meta->display_key ) . ':</span> ' . esc_html( $value );
                    }
                    echo '<span class="mt-1 text-xs text-muted">' . implode( ', ', $meta_parts ) . '</span>';
                }

                do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order, false );
            @endphp
        </div>
        <div class="flex items-center justify-between text-sm">
            <span class="text-muted">
                {!! apply_filters( 'woocommerce_order_item_quantity_html', sprintf( '&times;&nbsp;%s', $qty_display ), $item ) !!}
            </span>
            <span class="woocommerce-table__product-total product-total font-semibold text-foreground">
                {!! $order->get_formatted_line_subtotal( $item ) !!}
            </span>
        </div>
    </div>
</div>

@if ( $show_purchase_note && $purchase_note )
    <div class="woocommerce-table__product-purchase-note product-purchase-note px-4 py-3 mb-2 bg-surface rounded-lg text-sm text-muted">
        {!! wpautop( do_shortcode( wp_kses_post( $purchase_note ) ) ) !!}
    </div>
@endif