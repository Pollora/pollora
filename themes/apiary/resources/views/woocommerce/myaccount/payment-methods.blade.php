{{--
 * Payment methods
 *
 * Shows customer payment methods on the account page.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.9.0
 --}}
@php

    $icons = config('theme.woocommerce.myaccount.icons', []);
    $saved_methods = wc_get_customer_saved_methods_list( get_current_user_id() );
    $has_methods   = (bool) $saved_methods;
    $types         = wc_get_account_payment_methods_types();

    do_action( 'woocommerce_before_account_payment_methods', $has_methods );
@endphp

@if ( $has_methods )

    {{-- Desktop table --}}
    <div class="hidden sm:block rounded-xl border border-outline overflow-hidden">
        <table class="woocommerce-MyAccount-paymentMethods shop_table shop_table_responsive account-payment-methods-table w-full">
            <thead>
                <tr class="border-b border-outline bg-surface">
                    @foreach ( wc_get_account_payment_methods_columns() as $column_id => $column_name )
                        <th class="woocommerce-PaymentMethod woocommerce-PaymentMethod--{{ esc_attr( $column_id ) }} payment-method-{{ esc_attr( $column_id ) }} px-5 py-3 text-left text-xs font-semibold text-muted uppercase tracking-wider">
                            <span class="nobr">{!! esc_html( $column_name ) !!}</span>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-outline">
                @foreach ( $saved_methods as $type => $methods )
                    @foreach ( $methods as $method )
                        <tr class="payment-method{{ ! empty( $method['is_default'] ) ? ' default-payment-method bg-surface/30' : '' }} hover:bg-surface/50 transition-colors">
                            @foreach ( wc_get_account_payment_methods_columns() as $column_id => $column_name )
                                <td class="woocommerce-PaymentMethod woocommerce-PaymentMethod--{{ esc_attr( $column_id ) }} payment-method-{{ esc_attr( $column_id ) }} px-5 py-4 text-sm" data-title="{{ esc_attr( $column_name ) }}">
                                    @php
                                        if ( has_action( 'woocommerce_account_payment_methods_column_' . $column_id ) ) {
                                            do_action( 'woocommerce_account_payment_methods_column_' . $column_id, $method );
                                        } elseif ( 'method' === $column_id ) {
                                            if ( ! empty( $method['method']['last4'] ) ) {
                                                echo '<span class="font-medium text-foreground">' . sprintf( esc_html__( '%1$s ending in %2$s', 'woocommerce' ), esc_html( wc_get_credit_card_type_label( $method['method']['brand'] ) ), '<strong>' . esc_html( $method['method']['last4'] ) . '</strong>' ) . '</span>';
                                            } else {
                                                echo '<span class="font-medium text-foreground">' . esc_html( wc_get_credit_card_type_label( $method['method']['brand'] ) ) . '</span>';
                                            }
                                            if ( ! empty( $method['is_default'] ) ) {
                                                echo ' <span class="ml-2 inline-flex items-center rounded-full bg-success-light px-2 py-0.5 text-xs font-medium text-success ring-1 ring-inset ring-success/20">' . esc_html__( 'Default', 'woocommerce' ) . '</span>';
                                            }
                                        } elseif ( 'expires' === $column_id ) {
                                            echo '<span class="text-muted">' . esc_html( $method['expires'] ) . '</span>';
                                        } elseif ( 'actions' === $column_id ) {
                                            foreach ( $method['actions'] as $key => $action ) {
                                                $btn_class = $key === 'delete'
                                                    ? 'border border-error/20 text-error hover:bg-error-light'
                                                    : 'border border-outline text-muted hover:bg-surface-alt hover:text-foreground';
                                                echo '<a href="' . esc_url( $action['url'] ) . '" class="inline-flex items-center rounded-lg px-3 py-1.5 text-xs font-medium transition-colors ' . $btn_class . ' ' . sanitize_html_class( $key ) . ' mr-1">' . esc_html( $action['name'] ) . '</a>';
                                            }
                                        }
                                    @endphp
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Mobile cards --}}
    <div class="sm:hidden space-y-3">
        @foreach ( $saved_methods as $type => $methods )
            @foreach ( $methods as $method )
                <div class="rounded-xl border border-outline bg-white p-4 {{ ! empty( $method['is_default'] ) ? 'ring-2 ring-primary/20' : '' }}">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            @if ( ! empty( $method['method']['last4'] ) )
                                <span class="text-sm font-semibold text-foreground">
                                    {!! sprintf( esc_html__( '%1$s ending in %2$s', 'woocommerce' ), esc_html( wc_get_credit_card_type_label( $method['method']['brand'] ) ), '<strong>' . esc_html( $method['method']['last4'] ) . '</strong>' ) !!}
                                </span>
                            @else
                                <span class="text-sm font-semibold text-foreground">{!! esc_html( wc_get_credit_card_type_label( $method['method']['brand'] ) ) !!}</span>
                            @endif
                        </div>
                        @if ( ! empty( $method['is_default'] ) )
                            <span class="inline-flex items-center rounded-full bg-success-light px-2 py-0.5 text-xs font-medium text-success ring-1 ring-inset ring-success/20">@php esc_html_e( 'Default', 'woocommerce' ); @endphp</span>
                        @endif
                    </div>
                    <p class="text-xs text-muted mb-3">@php esc_html_e( 'Expires', 'woocommerce' ); @endphp {!! esc_html( $method['expires'] ) !!}</p>
                    <div class="flex gap-2">
                        @foreach ( $method['actions'] as $key => $action )
                            @php
                                $btn_class = $key === 'delete'
                                    ? 'border border-error/20 text-error hover:bg-error-light'
                                    : 'border border-outline text-muted hover:bg-surface-alt hover:text-foreground';
                            @endphp
                            <a href="{{ esc_url( $action['url'] ) }}"
                               class="inline-flex items-center rounded-lg px-3 py-1.5 text-xs font-medium transition-colors {{ $btn_class }} {{ sanitize_html_class( $key ) }}">
                                {{ $action['name'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endforeach
    </div>

@else

    {{-- Empty state --}}
    <div class="rounded-xl border border-dashed border-outline bg-surface/50 py-12 text-center">
        @if (!empty($icons['empty-payment']))
            <span class="flex justify-center text-subtle">{!! $icons['empty-payment'] !!}</span>
        @endif
        <h3 class="mt-4 text-sm font-semibold text-foreground">@php esc_html_e( 'No saved methods found.', 'woocommerce' ); @endphp</h3>
        <p class="mt-1 text-sm text-muted">@php esc_html_e( 'Add a payment method for faster checkout.', 'woocommerce' ); @endphp</p>
    </div>

@endif

@php do_action( 'woocommerce_after_account_payment_methods', $has_methods ); @endphp

@if ( WC()->payment_gateways->get_available_payment_gateways() )
    <div class="mt-6">
        <a class="inline-flex items-center rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-primary-hover transition-colors"
           href="{{ esc_url( wc_get_endpoint_url( 'add-payment-method' ) ) }}">
            @php esc_html_e( 'Add payment method', 'woocommerce' ); @endphp
        </a>
    </div>
@endif
