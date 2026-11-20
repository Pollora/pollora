{{--
 * Lost password form
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.2.0
 --}}
@php
    do_action( 'woocommerce_before_lost_password_form' );
@endphp

<div class="max-w-md mx-auto" id="customer_login">
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-foreground">@php esc_html_e( 'Reset your password', 'woocommerce' ); @endphp</h2>
        <p class="mt-2 text-sm text-muted">
            {!! apply_filters( 'woocommerce_lost_password_message', esc_html__( 'Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.', 'woocommerce' ) ) !!}
        </p>
    </div>

    <div class="rounded-xl border border-outline bg-white p-6 sm:p-8">
        <form method="post" class="woocommerce-ResetPassword lost_reset_password space-y-5">
            <div>
                <label for="user_login" class="block text-sm font-medium text-foreground">
                    @php esc_html_e( 'Username or email', 'woocommerce' ); @endphp&nbsp;<span class="text-error">*</span>
                </label>
                <input class="woocommerce-Input woocommerce-Input--text input-text mt-1.5 block w-full rounded-lg border border-outline px-3.5 py-2.5 text-sm text-foreground shadow-xs placeholder:text-subtle focus:border-ring focus:ring-2 focus:ring-ring/20 focus:outline-hidden"
                       type="text" name="user_login" id="user_login" autocomplete="username" required aria-required="true" />
            </div>

            @php do_action( 'woocommerce_lostpassword_form' ); @endphp

            <div>
                <input type="hidden" name="wc_reset_password" value="true" />
                <button type="submit"
                        class="woocommerce-Button button w-full rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-primary-hover focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-ring transition-colors"
                        value="@php esc_attr_e( 'Reset password', 'woocommerce' ); @endphp">
                    @php esc_html_e( 'Reset password', 'woocommerce' ); @endphp
                </button>
            </div>

            @php wp_nonce_field( 'lost_password', 'woocommerce-lost-password-nonce' ); @endphp
        </form>
    </div>
</div>

@php do_action( 'woocommerce_after_lost_password_form' ); @endphp
