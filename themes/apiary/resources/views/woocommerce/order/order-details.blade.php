{{--
 * Order details
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.1.0
 --}}
@php

    $order = wc_get_order( $order_id );
    if ( ! $order ) {
        return;
    }

    $order_items           = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
    $show_purchase_note    = $order->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );
    $show_customer_details = $order->get_user_id() === get_current_user_id();
    $downloads             = $order->get_downloadable_items();
    $show_downloads        = $order->has_downloadable_item() && $order->is_download_permitted();
    $actions               = array_filter(
        wc_get_account_orders_actions( $order ),
        function ( $key ) {
            return 'view' !== $key;
        },
        ARRAY_FILTER_USE_KEY
    );

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

{{-- Order status progress --}}
@if (!$order->has_status( 'failed' ))
    @php
        $statuses = wc_get_current_order_statuses($order);
        $status_percent = wc_get_order_status_progress($order);

        $grid_column_class = match(count($statuses)) {
            2 => 'grid-cols-2',
            3 => 'grid-cols-3',
            4 => 'grid-cols-4',
            5 => 'grid-cols-5',
            6 => 'grid-cols-6',
            default => 'grid-cols-1',
        };
    @endphp

    <div class="mt-6 mb-8">
        <div class="bg-surface-alt rounded-full overflow-hidden">
            <div class="h-2 bg-primary rounded-full transition-all" style="width: {{ $status_percent }}%"></div>
        </div>
        <div class="hidden sm:grid {{ $grid_column_class }} text-xs font-medium text-muted mt-3">
            @foreach($statuses as $status)
                <div class="text-primary @if (!$loop->first && !$loop->last) text-center @elseif ($loop->last) text-right @endif">{{ $status }}</div>
            @endforeach
        </div>
    </div>
@endif

{{-- Order details section --}}
<section class="woocommerce-order-details">
    @php do_action( 'woocommerce_order_details_before_order_table', $order ); @endphp

    <div class="rounded-xl border border-outline bg-white overflow-hidden">
        {{-- Section header --}}
        <div class="px-5 py-4 border-b border-outline bg-surface">
            <h2 class="woocommerce-order-details__title text-sm font-semibold text-foreground !m-0">
                @php esc_html_e( 'Order details', 'woocommerce' ); @endphp
            </h2>
        </div>

        {{-- Product items --}}
        <div class="woocommerce-table woocommerce-table--order-details shop_table order_details px-5">
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

            {{-- Order totals --}}
            <div class="border-t border-outline py-5 space-y-3">
                @foreach ( $order->get_order_item_totals() as $key => $total )
                    @php
                        if ( in_array($key, ['payment_method', 'shipping']) ) {
                            continue;
                        }
                    @endphp
                    <div class="flex justify-between text-sm {{ $key === 'order_total' ? 'pt-3 border-t border-outline font-semibold text-foreground' : 'text-muted' }}">
                        <dt>{{ __( $total['label'] ) }}</dt>
                        <dd>{!! wp_kses_post( $total['value'] ) !!}</dd>
                    </div>
                @endforeach
            </div>

            {{-- Order actions --}}
            @if ( ! empty( $actions ) )
                <div class="border-t border-outline px-5 py-4 flex flex-wrap gap-2">
                    @foreach ( $actions as $key => $action )
                        @php
                            $action_aria_label = ! empty( $action['aria-label'] )
                                ? $action['aria-label']
                                : sprintf( __( '%1$s order number %2$s', 'woocommerce' ), $action['name'], $order->get_order_number() );
                        @endphp
                        <a href="{{ esc_url( $action['url'] ) }}"
                           class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg border border-outline text-foreground hover:bg-surface transition-colors {{ sanitize_html_class( $key ) }}"
                           aria-label="{{ esc_attr( $action_aria_label ) }}">
                            {{ esc_html( $action['name'] ) }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Shipping, payment & customer details — single grid --}}
    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
        @foreach ( $order->get_order_item_totals() as $key => $total )
            @if ( in_array($key, ['payment_method', 'shipping']) )
                <div class="rounded-xl border border-outline bg-white p-5">
                    <dt class="!mt-0 text-xs font-semibold text-muted uppercase tracking-wider">{{ __( $total['label'] ) }}</dt>
                    <dd class="!pl-0 mt-2 text-sm text-foreground">
                        {!! ( 'payment_method' === $key ) ? esc_html( $total['value'] ) : wp_kses_post( $total['value'] ) !!}
                    </dd>
                </div>
            @endif
        @endforeach

        @if ( $show_customer_details )
            {!! wc_get_template( 'order/order-details-customer.php', array( 'order' => $order ) ) !!}
        @endif
    </div>

    {{-- Customer note --}}
    @if ( $order->get_customer_note() )
        <div class="mt-4 rounded-xl border border-outline bg-surface/50 p-5">
            <div class="!mt-0 text-xs font-semibold text-muted uppercase tracking-wider mb-2">@php esc_html_e( 'Note', 'woocommerce' ); @endphp</div>
            <div class="text-sm text-foreground">{!! wp_kses_post( nl2br( wc_wptexturize_order_note( $order->get_customer_note() ) ) ) !!}</div>
        </div>
    @endif

    @php do_action( 'woocommerce_order_details_after_order_table', $order ); @endphp
</section>

@php do_action( 'woocommerce_after_order_details', $order ); @endphp
