{{--
 * Lost password reset form.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.2.0
 --}}
@php
    do_action( 'woocommerce_before_reset_password_form' );
@endphp

<div class="max-w-md mx-auto">
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-foreground">@php esc_html_e( 'Set new password', 'woocommerce' ); @endphp</h2>
        <p class="mt-2 text-sm text-muted">
            {!! apply_filters( 'woocommerce_reset_password_message', esc_html__( 'Enter a new password below.', 'woocommerce' ) ) !!}
        </p>
    </div>

    <div class="rounded-xl border border-outline bg-white p-6 sm:p-8">
        <form method="post" class="woocommerce-ResetPassword lost_reset_password space-y-5">
            <div>
                <label for="password_1" class="block text-sm font-medium text-foreground">
                    @php esc_html_e( 'New password', 'woocommerce' ); @endphp&nbsp;<span class="text-error">*</span>
                </label>
                <input type="password"
                       class="woocommerce-Input woocommerce-Input--text input-text mt-1.5 block w-full rounded-lg border border-outline px-3.5 py-2.5 text-sm text-foreground shadow-xs placeholder:text-subtle focus:border-ring focus:ring-2 focus:ring-ring/20 focus:outline-hidden"
                       name="password_1" id="password_1" autocomplete="new-password" required aria-required="true" />
            </div>

            <div>
                <label for="password_2" class="block text-sm font-medium text-foreground">
                    @php esc_html_e( 'Re-enter new password', 'woocommerce' ); @endphp&nbsp;<span class="text-error">*</span>
                </label>
                <input type="password"
                       class="woocommerce-Input woocommerce-Input--text input-text mt-1.5 block w-full rounded-lg border border-outline px-3.5 py-2.5 text-sm text-foreground shadow-xs placeholder:text-subtle focus:border-ring focus:ring-2 focus:ring-ring/20 focus:outline-hidden"
                       name="password_2" id="password_2" autocomplete="new-password" required aria-required="true" />
            </div>

            <input type="hidden" name="reset_key" value="{{ esc_attr( $args['key'] ) }}" />
            <input type="hidden" name="reset_login" value="{{ esc_attr( $args['login'] ) }}" />

            @php do_action( 'woocommerce_resetpassword_form' ); @endphp

            <div>
                <input type="hidden" name="wc_reset_password" value="true" />
                <button type="submit"
                        class="woocommerce-Button button w-full rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-primary-hover focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-ring transition-colors"
                        value="@php esc_attr_e( 'Save', 'woocommerce' ); @endphp">
                    @php esc_html_e( 'Save', 'woocommerce' ); @endphp
                </button>
            </div>

            @php wp_nonce_field( 'reset_password', 'woocommerce-reset-password-nonce' ); @endphp
        </form>
    </div>
</div>

@php do_action( 'woocommerce_after_reset_password_form' ); @endphp
