{{--
 * Add payment method form
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.8.0
 --}}
@php
    $available_gateways = WC()->payment_gateways->get_available_payment_gateways();
@endphp

@if ( $available_gateways )

    <div class="rounded-xl border border-outline bg-white p-5 sm:p-8">
        <h2 class="mt-0 text-lg font-bold text-foreground mb-6">@php esc_html_e( 'Add payment method', 'woocommerce' ); @endphp</h2>

        <form id="add_payment_method" method="post">
            <div id="payment" class="woocommerce-Payment">
                <ul class="woocommerce-PaymentMethods payment_methods methods space-y-3 list-none pl-0">
                    @php
                        if ( count( $available_gateways ) ) {
                            current( $available_gateways )->set_current();
                        }
                    @endphp
                    @foreach ( $available_gateways as $gateway )
                        <li class="woocommerce-PaymentMethod woocommerce-PaymentMethod--{{ esc_attr( $gateway->id ) }} payment_method_{{ esc_attr( $gateway->id ) }} rounded-lg border border-outline p-4 has-[:checked]:border-primary has-[:checked]:bg-primary/5 transition-colors">
                            <label for="payment_method_{{ esc_attr( $gateway->id ) }}" class="flex items-center gap-3 cursor-pointer">
                                <input id="payment_method_{{ esc_attr( $gateway->id ) }}"
                                       type="radio"
                                       class="input-radio size-4 border-outline text-primary focus:ring-ring"
                                       name="payment_method"
                                       value="{{ esc_attr( $gateway->id ) }}"
                                       @php checked( $gateway->chosen, true ); @endphp />
                                <span class="text-sm font-medium text-foreground">
                                    {!! wp_kses_post( $gateway->get_title() ) !!}
                                </span>
                                {!! wp_kses_post( $gateway->get_icon() ) !!}
                            </label>
                            @if ( $gateway->has_fields() || $gateway->get_description() )
                                <div class="woocommerce-PaymentBox woocommerce-PaymentBox--{{ esc_attr( $gateway->id ) }} payment_box payment_method_{{ esc_attr( $gateway->id ) }} mt-3 pl-7 text-sm text-muted"
                                     style="display: none;">
                                    @php $gateway->payment_fields(); @endphp
                                </div>
                            @endif
                        </li>
                    @endforeach
                </ul>

                @php do_action( 'woocommerce_add_payment_method_form_bottom' ); @endphp

                <div class="mt-6 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3">
                    @php wp_nonce_field( 'woocommerce-add-payment-method', 'woocommerce-add-payment-method-nonce' ); @endphp
                    <a href="{{ esc_url( wc_get_endpoint_url( 'payment-methods' ) ) }}"
                       class="inline-flex items-center justify-center rounded-lg border border-outline bg-white px-4 py-2.5 text-sm font-medium text-muted shadow-xs hover:bg-surface-alt hover:text-foreground transition-colors">
                        @php esc_html_e( 'Cancel', 'woocommerce' ); @endphp
                    </a>
                    <button type="submit"
                            class="woocommerce-Button woocommerce-Button--alt button alt inline-flex items-center justify-center rounded-lg bg-primary px-6 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-primary-hover focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-ring transition-colors"
                            id="place_order"
                            value="@php esc_attr_e( 'Add payment method', 'woocommerce' ); @endphp">
                        @php esc_html_e( 'Add payment method', 'woocommerce' ); @endphp
                    </button>
                    <input type="hidden" name="woocommerce_add_payment_method" id="woocommerce_add_payment_method" value="1" />
                </div>
            </div>
        </form>
    </div>

@else

    <div class="rounded-xl border border-dashed border-outline bg-surface/50 py-12 text-center">
        <p class="text-sm text-muted">@php esc_html_e( 'New payment methods can only be added during checkout. Please contact us if you require assistance.', 'woocommerce' ); @endphp</p>
    </div>

@endif
