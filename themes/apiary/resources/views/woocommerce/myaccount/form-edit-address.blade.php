{{--
 * Edit address form
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.3.0
 --}}
@php
    $page_title = ( 'billing' === $load_address ) ? esc_html__( 'Billing address', 'woocommerce' ) : esc_html__( 'Shipping address', 'woocommerce' );
    do_action( 'woocommerce_before_edit_account_address_form' );
@endphp

@if ( ! $load_address )
    @php wc_get_template( 'myaccount/my-address.php' ); @endphp
@else
    <div class="rounded-xl border border-outline bg-white p-5 sm:p-8">
        <h2 class="mt-0 text-xl font-bold text-foreground">
            {!! apply_filters( 'woocommerce_my_account_edit_address_title', $page_title, $load_address ) !!}
        </h2>

        <form method="post" class="mt-6">
            <div class="woocommerce-address-fields">
                @php do_action( "woocommerce_before_edit_address_form_{$load_address}" ); @endphp
                <div class="woocommerce-address-fields__field-wrapper grid grid-cols-1 gap-y-5 sm:grid-cols-2 sm:gap-x-4">
                    @php
                    foreach ( $address as $key => $field ) {
                        woocommerce_form_field( $key, $field, wc_get_post_data_by_key( $key, $field['value'] ) );
                    }
                    @endphp
                </div>
                @php do_action( "woocommerce_after_edit_address_form_{$load_address}" ); @endphp

                <div class="mt-8 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3">
                    <a href="{{ esc_url( wc_get_endpoint_url( 'edit-address' ) ) }}"
                       class="inline-flex items-center justify-center rounded-lg border border-outline bg-white px-4 py-2.5 text-sm font-medium text-muted shadow-xs hover:bg-surface-alt hover:text-foreground transition-colors">
                        @php esc_html_e( 'Cancel', 'woocommerce' ); @endphp
                    </a>
                    <button type="submit"
                            class="button inline-flex items-center justify-center rounded-lg bg-primary px-6 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-primary-hover focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-ring transition-colors"
                            name="save_address"
                            value="@php esc_attr_e( 'Save address', 'woocommerce' ); @endphp">
                        @php esc_html_e( 'Save address', 'woocommerce' ); @endphp
                    </button>
                    @php wp_nonce_field( 'woocommerce-edit_address', 'woocommerce-edit-address-nonce' ); @endphp
                    <input type="hidden" name="action" value="edit_address" />
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </div>
            </div>
        </form>
    </div>
@endif

@php do_action( 'woocommerce_after_edit_account_address_form' ); @endphp
