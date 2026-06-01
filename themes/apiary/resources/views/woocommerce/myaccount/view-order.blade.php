{{--
 * View Order
 *
 * Shows the details of a particular order on the account page.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.6.0
 --}}
@php
    $icons = config('theme.woocommerce.myaccount.icons', []);
    $notes = $order->get_customer_order_notes();
    $status = $order->get_status();
    $status_colors = config('theme.woocommerce.myaccount.order_status_colors', []);
    $badge_class = $status_colors[$status] ?? 'bg-surface-alt text-muted ring-outline';
@endphp

{{-- Order header --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
    <div>
        <h2 class="mt-0 text-xl font-bold text-foreground">
            @php printf( esc_html__( 'Order #%s', 'woocommerce' ), $order->get_order_number() ); @endphp
        </h2>
        <p class="mt-1 text-sm text-muted">
            <time datetime="{{ esc_attr( $order->get_date_created()->date( 'c' ) ) }}">
                {!! esc_html( wc_format_datetime( $order->get_date_created() ) ) !!}
            </time>
        </p>
    </div>
    <span class="inline-flex self-start items-center rounded-full px-3 py-1 text-sm font-medium ring-1 ring-inset {{ $badge_class }}">
        {!! esc_html( wc_get_order_status_name( $status ) ) !!}
    </span>
</div>

{{-- Order updates timeline --}}
@if ( $notes )
    <div class="rounded-xl border border-outline bg-white p-5 sm:p-6 mb-6">
        <h3 class="text-base font-semibold text-foreground mb-4">
            @php esc_html_e( 'Order updates', 'woocommerce' ); @endphp
        </h3>
        <ol class="woocommerce-OrderUpdates commentlist notes relative space-y-6">
            @foreach ( $notes as $index => $note )
                <li class="woocommerce-OrderUpdate comment note relative {{ $index < count($notes) - 1 ? 'pb-6' : '' }}">
                    @if ($index < count($notes) - 1)
                        <span class="absolute left-4 top-9 -ml-px h-full w-0.5 bg-outline"></span>
                    @endif
                    <div class="relative flex gap-4">
                        <div class="flex size-8 shrink-0 items-center justify-center rounded-full bg-surface-alt ring-4 ring-white">
                            @if (!empty($icons['comment']))
                                <span class="text-muted">{!! $icons['comment'] !!}</span>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-medium text-muted">
                                {!! date_i18n( esc_html__( 'l jS \o\f F Y, h:ia', 'woocommerce' ), strtotime( $note->comment_date ) ) !!}
                            </p>
                            <div class="mt-1 text-sm text-foreground prose prose-sm max-w-none">
                                {!! wp_kses_post( wpautop( wptexturize( $note->comment_content ) ) ) !!}
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ol>
    </div>
@endif

{{-- Order details (rendered by WooCommerce hooks) --}}
<div>
    @php do_action( 'woocommerce_view_order', $order_id ); @endphp
</div>
