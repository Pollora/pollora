{{--
 * Lost password confirmation text.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.9.0
 --}}
@php
    wc_print_notice( esc_html__( 'Password reset email has been sent.', 'woocommerce' ) );
@endphp

<div class="max-w-md mx-auto">
    @php do_action( 'woocommerce_before_lost_password_confirmation_message' ); @endphp

    <div class="rounded-xl border border-outline bg-white p-6 sm:p-8 text-center">
        <div class="mx-auto flex size-12 items-center justify-center rounded-full bg-success-light">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-success">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
            </svg>
        </div>
        <h3 class="mt-4 text-base font-semibold text-foreground">@php esc_html_e( 'Check your email', 'woocommerce' ); @endphp</h3>
        <p class="mt-2 text-sm text-muted leading-relaxed">
            {!! esc_html( apply_filters( 'woocommerce_lost_password_confirmation_message', esc_html__( 'A password reset email has been sent to the email address on file for your account, but may take several minutes to show up in your inbox. Please wait at least 10 minutes before attempting another reset.', 'woocommerce' ) ) ) !!}
        </p>
    </div>

    @php do_action( 'woocommerce_after_lost_password_confirmation_message' ); @endphp
</div>
