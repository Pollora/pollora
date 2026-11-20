{{--
 * Thankyou page
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.1.0
 --}}

<div class="woocommerce-order max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    @if ( $order )
        @php do_action( 'woocommerce_before_thankyou', $order->get_id() ); @endphp

        @if ( $order->has_status( 'failed' ) )
            {{-- Failed order --}}
            <div class="py-12 text-center">
                <div class="mx-auto flex size-16 items-center justify-center rounded-full bg-error-light">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8 text-error">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                </div>
                <h1 class="mt-4 text-2xl font-bold text-foreground">
                    @php esc_html_e( 'Payment failed', 'woocommerce' ); @endphp
                </h1>
                <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed mt-2 text-sm text-muted max-w-md mx-auto">
                    {{ __( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ) }}
                </p>
                <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="{{ esc_url( $order->get_checkout_payment_url() ) }}"
                       class="button pay inline-flex items-center justify-center rounded-lg bg-primary px-6 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-primary-hover transition-colors">
                        {{ __( 'Try again', 'woocommerce' ) }}
                    </a>
                    @if ( is_user_logged_in() )
                        <a href="{{ esc_url( wc_get_page_permalink( 'myaccount' ) ) }}"
                           class="inline-flex items-center justify-center rounded-lg border border-outline bg-white px-6 py-2.5 text-sm font-medium text-muted shadow-xs hover:bg-surface-alt hover:text-foreground transition-colors">
                            {{ __( 'My account', 'woocommerce' ) }}
                        </a>
                    @endif
                </div>
            </div>

        @else
            {{-- Successful order --}}
            <div class="py-10 sm:py-14 text-center">
                {{-- Success icon --}}
                <div class="mx-auto flex size-16 items-center justify-center rounded-full bg-success-light ring-8 ring-success-light/50">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-8 text-success">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                </div>

                <div class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received mt-6 text-2xl sm:text-3xl font-bold text-foreground">
                    @php wc_get_template( 'checkout/order-received.php', array( 'order' => $order ) ); @endphp
                </div>
                <p class="mt-2 text-sm text-muted">
                    @php printf( esc_html__( 'Order #%s', 'woocommerce' ), '<strong class="text-foreground">' . $order->get_order_number() . '</strong>' ); @endphp
                </p>
            </div>

            {{-- Order summary cards --}}
            <div class="woocommerce-order-overview woocommerce-thankyou-order-details order_details grid grid-cols-2 sm:grid-cols-4 gap-3 mb-8 list-none !pl-0">
                <div class="woocommerce-order-overview__date date rounded-xl border border-outline bg-white p-4 text-center">
                    <div class="text-xs font-medium text-muted uppercase tracking-wider">@php esc_html_e( 'Date', 'woocommerce' ); @endphp</div>
                    <div class="mt-1.5 text-sm font-semibold text-foreground">{!! wc_format_datetime( $order->get_date_created() ) !!}</div>
                </div>
                <div class="woocommerce-order-overview__total total rounded-xl border border-outline bg-white p-4 text-center">
                    <div class="text-xs font-medium text-muted uppercase tracking-wider">@php esc_html_e( 'Total', 'woocommerce' ); @endphp</div>
                    <div class="mt-1.5 text-sm font-semibold text-foreground">{!! $order->get_formatted_order_total() !!}</div>
                </div>
                @if ( $order->get_payment_method_title() )
                    <div class="woocommerce-order-overview__payment-method method rounded-xl border border-outline bg-white p-4 text-center">
                        <div class="text-xs font-medium text-muted uppercase tracking-wider">@php esc_html_e( 'Payment', 'woocommerce' ); @endphp</div>
                        <div class="mt-1.5 text-sm font-semibold text-foreground">{!! wp_kses_post( $order->get_payment_method_title() ) !!}</div>
                    </div>
                @endif
                @if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() )
                    <div class="woocommerce-order-overview__email email rounded-xl border border-outline bg-white p-4 text-center">
                        <div class="text-xs font-medium text-muted uppercase tracking-wider">@php esc_html_e( 'Email', 'woocommerce' ); @endphp</div>
                        <div class="mt-1.5 text-sm font-semibold text-foreground truncate">{!! $order->get_billing_email() !!}</div>
                    </div>
                @endif
            </div>

            {{-- Payment method notice (bank details etc.) --}}
            <div class="text-sm text-muted [&_h2]:text-base [&_h2]:font-semibold [&_h2]:text-foreground [&_h2]:!mt-0 [&_h2]:mb-3 [&_table]:w-full [&_table]:rounded-xl [&_table]:border [&_table]:border-outline [&_table]:overflow-hidden [&_th]:px-4 [&_th]:py-2.5 [&_th]:text-left [&_th]:text-xs [&_th]:font-semibold [&_th]:text-muted [&_th]:uppercase [&_th]:bg-surface [&_td]:px-4 [&_td]:py-2.5 [&_td]:text-sm [&_td]:border-t [&_td]:border-outline mb-8">
                @php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); @endphp
            </div>
        @endif

        {{-- Order details (products, totals, addresses) --}}
        @php do_action( 'woocommerce_thankyou', $order->get_id() ); @endphp

        {{-- CTA --}}
        @if ( is_user_logged_in() )
            <div class="mt-8 mb-12 flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ esc_url( wc_get_endpoint_url( 'view-order', $order->get_id(), wc_get_page_permalink( 'myaccount' ) ) ) }}"
                   class="inline-flex items-center justify-center rounded-lg bg-primary px-6 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-primary-hover transition-colors">
                    @php esc_html_e( 'View order details', 'woocommerce' ); @endphp
                </a>
                <a href="{{ esc_url( wc_get_page_permalink( 'shop' ) ) }}"
                   class="inline-flex items-center justify-center rounded-lg border border-outline bg-white px-6 py-2.5 text-sm font-medium text-muted shadow-xs hover:bg-surface-alt hover:text-foreground transition-colors">
                    @php esc_html_e( 'Continue shopping', 'woocommerce' ); @endphp
                </a>
            </div>
        @endif

    @else
        {{-- No order --}}
        <div class="py-12 text-center">
            <div class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received text-lg text-muted">
                @php wc_get_template( 'checkout/order-received.php', array( 'order' => false ) ); @endphp
            </div>
        </div>
    @endif
</div>
