{{--
 * Orders
 *
 * Shows orders on the account page.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.5.0
 --}}
@php
    $icons = config('theme.woocommerce.myaccount.icons', []);
    $status_colors = config('theme.woocommerce.myaccount.order_status_colors', []);
    do_action( 'woocommerce_before_account_orders', $has_orders );
@endphp

@if ( $has_orders )

    {{-- Desktop table --}}
    <div class="hidden sm:block rounded-xl border border-outline overflow-hidden">
        <table class="!my-0 woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table w-full">
            <thead>
                <tr class="border-b border-outline bg-surface">
                    @foreach ( wc_get_account_orders_columns() as $column_id => $column_name )
                        <th scope="col"
                            class="woocommerce-orders-table__header woocommerce-orders-table__header-{{ esc_attr( $column_id ) }} px-5 py-3 text-left text-xs font-semibold text-muted uppercase tracking-wider">
                            <span class="nobr">{!! esc_html( $column_name ) !!}</span>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-outline">
                @foreach ( $customer_orders->orders as $customer_order )
                    @php
                        $order = wc_get_order( $customer_order );
                        $item_count = $order->get_item_count() - $order->get_item_count_refunded();
                    @endphp
                    <tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-{{ esc_attr( $order->get_status() ) }} order hover:bg-surface/50 transition-colors">
                        @foreach ( wc_get_account_orders_columns() as $column_id => $column_name )
                            <td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-{{ esc_attr( $column_id ) }} px-5 py-4 text-sm" data-title="{{ esc_attr( $column_name ) }}">
                                @if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) )
                                    @php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); @endphp
                                @elseif ( 'order-number' === $column_id )
                                    <a href="{{ esc_url( $order->get_view_order_url() ) }}" class="font-semibold text-foreground hover:text-primary">
                                        #{{ $order->get_order_number() }}
                                    </a>
                                @elseif ( 'order-date' === $column_id )
                                    <time class="text-muted" datetime="{{ esc_attr( $order->get_date_created()->date( 'c' ) ) }}">
                                        {!! esc_html( wc_format_datetime( $order->get_date_created() ) ) !!}
                                    </time>
                                @elseif ( 'order-status' === $column_id )
                                    @php
                                        $status = $order->get_status();
                                        $badge_class = $status_colors[$status] ?? 'bg-surface-alt text-muted ring-outline';
                                    @endphp
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {{ $badge_class }}">
                                        {!! esc_html( wc_get_order_status_name( $status ) ) !!}
                                    </span>
                                @elseif ( 'order-total' === $column_id )
                                    <span class="font-medium text-foreground">
                                        {!! wp_kses_post( sprintf( _n( '%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count ) ) !!}
                                    </span>
                                @elseif ( 'order-actions' === $column_id )
                                    @php $actions = wc_get_account_orders_actions( $order ); @endphp
                                    @if ( ! empty( $actions ) )
                                        <div class="flex items-center gap-2">
                                            @foreach ( $actions as $key => $action )
                                                <a href="{{ esc_url( $action['url'] ) }}"
                                                   class="inline-flex items-center rounded-lg px-3 py-1.5 text-xs font-medium transition-colors
                                                          {{ $key === 'view' ? 'bg-primary text-white hover:bg-primary-hover' : 'border border-outline text-muted hover:bg-surface-alt hover:text-foreground' }}
                                                          {{ sanitize_html_class( $key ) }}">
                                                    {{ $action['name'] }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Mobile cards --}}
    <div class="sm:hidden space-y-3">
        @foreach ( $customer_orders->orders as $customer_order )
            @php
                $order = wc_get_order( $customer_order );
                $item_count = $order->get_item_count() - $order->get_item_count_refunded();
                $status = $order->get_status();
                $badge_class = $status_colors[$status] ?? 'bg-surface-alt text-muted ring-outline';
                $actions = wc_get_account_orders_actions( $order );
            @endphp
            <a href="{{ esc_url( $order->get_view_order_url() ) }}" class="block rounded-xl border border-outline bg-white p-4 transition-all active:bg-surface/50">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-semibold text-foreground">#{{ $order->get_order_number() }}</span>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {{ $badge_class }}">
                        {!! esc_html( wc_get_order_status_name( $status ) ) !!}
                    </span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <time class="text-muted" datetime="{{ esc_attr( $order->get_date_created()->date( 'c' ) ) }}">
                        {!! esc_html( wc_format_datetime( $order->get_date_created() ) ) !!}
                    </time>
                    <span class="font-medium text-foreground">{!! wp_kses_post( $order->get_formatted_order_total() ) !!}</span>
                </div>
                <div class="mt-1 text-xs text-muted">
                    {!! esc_html( sprintf( _n( '%s item', '%s items', $item_count, 'woocommerce' ), $item_count ) ) !!}
                </div>
            </a>
        @endforeach
    </div>

    @php do_action( 'woocommerce_before_account_orders_pagination' ); @endphp

    {{-- Pagination --}}
    @if ( 1 < $customer_orders->max_num_pages )
        <nav class="woocommerce-pagination woocommerce-Pagination mt-6 flex items-center justify-between">
            <div class="flex flex-1 justify-between sm:justify-end gap-3">
                @if ( 1 !== $current_page )
                    <a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button inline-flex items-center rounded-lg border border-outline bg-white px-4 py-2 text-sm font-medium text-muted shadow-xs hover:bg-surface-alt hover:text-foreground transition-colors"
                       href="{{ esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ) }}">
                        @if (!empty($icons['chevron-left'])) <span class="mr-1.5">{!! $icons['chevron-left'] !!}</span> @endif
                        @php esc_html_e( 'Previous', 'woocommerce' ); @endphp
                    </a>
                @endif
                @if ( intval( $customer_orders->max_num_pages ) !== $current_page )
                    <a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button inline-flex items-center rounded-lg border border-outline bg-white px-4 py-2 text-sm font-medium text-muted shadow-xs hover:bg-surface-alt hover:text-foreground transition-colors"
                       href="{{ esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ) }}">
                        @php esc_html_e( 'Next', 'woocommerce' ); @endphp
                        @if (!empty($icons['chevron-right'])) <span class="ml-1.5">{!! $icons['chevron-right'] !!}</span> @endif
                    </a>
                @endif
            </div>
        </nav>
    @endif

@else

    {{-- Empty state --}}
    <div class="rounded-xl border border-dashed border-outline bg-surface/50 py-12 text-center">
        @if (!empty($icons['empty-orders']))
            <span class="flex justify-center text-subtle">{!! $icons['empty-orders'] !!}</span>
        @endif
        <h3 class="mt-4 text-sm font-semibold text-foreground">@php esc_html_e( 'No order has been made yet.', 'woocommerce' ); @endphp</h3>
        <p class="mt-1 text-sm text-muted">@php esc_html_e( 'Browse our products and place your first order.', 'woocommerce' ); @endphp</p>
        <div class="mt-6">
            <a href="{{ esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ) }}"
               class="inline-flex items-center rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-primary-hover transition-colors">
                @php esc_html_e( 'Browse products', 'woocommerce' ); @endphp
            </a>
        </div>
    </div>

@endif

@php do_action( 'woocommerce_after_account_orders', $has_orders ); @endphp
