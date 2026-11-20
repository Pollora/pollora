{{--
 * My Addresses
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.3.0
 --}}
@php
    $customer_id = get_current_user_id();

    if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) {
        $get_addresses = apply_filters(
            'woocommerce_my_account_get_addresses',
            [
                'billing'  => __( 'Billing address', 'woocommerce' ),
                'shipping' => __( 'Shipping address', 'woocommerce' ),
            ],
            $customer_id
        );
    } else {
        $get_addresses = apply_filters(
            'woocommerce_my_account_get_addresses',
            [
                'billing' => __( 'Billing address', 'woocommerce' ),
            ],
            $customer_id
        );
    }

    $icons = config('theme.woocommerce.myaccount.icons', []);
@endphp

<p class="text-sm text-muted mb-6">
    {!! apply_filters( 'woocommerce_my_account_my_address_description', esc_html__( 'The following addresses will be used on the checkout page by default.', 'woocommerce' ) ) !!}
</p>

<div class="woocommerce-Addresses col2-set addresses grid grid-cols-1 {{ count($get_addresses) > 1 ? 'sm:grid-cols-2' : '' }} gap-4">
    @foreach ( $get_addresses as $name => $address_title )
        @php
            $address = wc_get_account_formatted_address( $name );
        @endphp
        <div class="woocommerce-Address rounded-xl border border-outline bg-white p-5 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    @if (!empty($icons[$name]))
                        <div class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-surface-alt text-muted">
                            {!! $icons[$name] !!}
                        </div>
                    @endif
                    <h3 class="woocommerce-column__title text-sm font-semibold text-foreground">{!! esc_html( $address_title ) !!}</h3>
                </div>
                <a href="{{ esc_url( wc_get_endpoint_url( 'edit-address', $name ) ) }}"
                   class="edit inline-flex items-center gap-1.5 rounded-lg border border-outline px-3 py-1.5 text-xs font-medium text-muted shadow-xs hover:bg-surface-alt hover:text-foreground transition-colors">
                    @if (!empty($icons['edit']))
                        {!! $icons['edit'] !!}
                    @endif
                    {!! $address ? esc_html__( 'Edit', 'woocommerce' ) : esc_html__( 'Add', 'woocommerce' ) !!}
                </a>
            </div>
            <address class="text-sm text-muted not-italic leading-relaxed">
                @if ($address)
                    {!! wp_kses_post( $address ) !!}
                @else
                    <span class="italic text-subtle">@php esc_html_e( 'You have not set up this type of address yet.', 'woocommerce' ); @endphp</span>
                @endif
            </address>
            @php do_action( 'woocommerce_my_account_after_my_address', $name ); @endphp
        </div>
    @endforeach
</div>
