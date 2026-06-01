{{--
 * Edit account form
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.5.0
 --}}
@php
    do_action( 'woocommerce_before_edit_account_form' );
@endphp

<form class="woocommerce-EditAccountForm edit-account" action="" method="post" @php do_action( 'woocommerce_edit_account_form_tag' ); @endphp>
    @php do_action( 'woocommerce_edit_account_form_start' ); @endphp

    {{-- Personal information --}}
    <div class="rounded-xl border border-outline bg-white p-5 sm:p-8">
        <h2 class="mt-0 text-lg font-bold text-foreground">
            @php esc_html_e( 'Personal information', 'woocommerce' ); @endphp
        </h2>
        <p class="mt-1 text-sm text-muted">@php esc_html_e( 'Update your name and email address.', 'woocommerce' ); @endphp</p>

        <div class="mt-6 grid grid-cols-1 gap-y-5 sm:grid-cols-2 sm:gap-x-4">
            <div class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
                <label for="account_first_name" class="block text-sm font-medium text-foreground mb-1.5">
                    @php esc_html_e( 'First name', 'woocommerce' ); @endphp&nbsp;<span class="text-error" aria-hidden="true">*</span>
                </label>
                <input type="text"
                       class="woocommerce-Input woocommerce-Input--text input-text block w-full rounded-lg border border-outline px-3.5 py-2.5 text-sm text-foreground shadow-xs placeholder:text-subtle focus:border-ring focus:ring-2 focus:ring-ring/20 focus:outline-hidden"
                       name="account_first_name" id="account_first_name" autocomplete="given-name"
                       value="{!! esc_attr( $user->first_name ) !!}" />
            </div>
            <div class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
                <label for="account_last_name" class="block text-sm font-medium text-foreground mb-1.5">
                    @php esc_html_e( 'Last name', 'woocommerce' ); @endphp&nbsp;<span class="text-error" aria-hidden="true">*</span>
                </label>
                <input type="text"
                       class="woocommerce-Input woocommerce-Input--text input-text block w-full rounded-lg border border-outline px-3.5 py-2.5 text-sm text-foreground shadow-xs placeholder:text-subtle focus:border-ring focus:ring-2 focus:ring-ring/20 focus:outline-hidden"
                       name="account_last_name" id="account_last_name" autocomplete="family-name"
                       value="{!! esc_attr( $user->last_name ) !!}" />
            </div>

            <div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide sm:col-span-2">
                <label for="account_display_name" class="block text-sm font-medium text-foreground mb-1.5">
                    @php esc_html_e( 'Display name', 'woocommerce' ); @endphp&nbsp;<span class="text-error" aria-hidden="true">*</span>
                </label>
                <input type="text"
                       class="woocommerce-Input woocommerce-Input--text input-text block w-full rounded-lg border border-outline px-3.5 py-2.5 text-sm text-foreground shadow-xs placeholder:text-subtle focus:border-ring focus:ring-2 focus:ring-ring/20 focus:outline-hidden"
                       name="account_display_name" id="account_display_name"
                       value="{!! esc_attr( $user->display_name ) !!}" />
                <p class="mt-2 text-xs text-muted">
                    @php esc_html_e( 'This will be how your name will be displayed in the account section and in reviews', 'woocommerce' ); @endphp
                </p>
            </div>

            <div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide sm:col-span-2">
                <label for="account_email" class="block text-sm font-medium text-foreground mb-1.5">
                    @php esc_html_e( 'Email address', 'woocommerce' ); @endphp&nbsp;<span class="text-error" aria-hidden="true">*</span>
                </label>
                <input type="email"
                       class="woocommerce-Input woocommerce-Input--email input-text block w-full rounded-lg border border-outline px-3.5 py-2.5 text-sm text-foreground shadow-xs placeholder:text-subtle focus:border-ring focus:ring-2 focus:ring-ring/20 focus:outline-hidden"
                       name="account_email" id="account_email" autocomplete="email"
                       value="{!! esc_attr( $user->user_email ) !!}" />
            </div>
            @php do_action( 'woocommerce_edit_account_form_fields' ); @endphp
        </div>
    </div>

    {{-- Password change --}}
    <div class="mt-6 rounded-xl border border-outline bg-white p-5 sm:p-8">
        <h2 class="mt-0 text-lg font-bold text-foreground">
            @php esc_html_e( 'Password change', 'woocommerce' ); @endphp
        </h2>
        <p class="mt-1 text-sm text-muted">@php esc_html_e( 'Leave blank to keep your current password.', 'woocommerce' ); @endphp</p>

        <div class="mt-6 grid grid-cols-1 gap-y-5 sm:grid-cols-2 sm:gap-x-4">
            <div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide sm:col-span-2">
                <label for="password_current" class="block text-sm font-medium text-foreground mb-1.5">
                    @php esc_html_e( 'Current password', 'woocommerce' ); @endphp
                </label>
                <input type="password"
                       class="woocommerce-Input woocommerce-Input--password input-text block w-full rounded-lg border border-outline px-3.5 py-2.5 text-sm text-foreground shadow-xs placeholder:text-subtle focus:border-ring focus:ring-2 focus:ring-ring/20 focus:outline-hidden"
                       name="password_current" id="password_current" autocomplete="current-password" />
            </div>
            <div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="password_1" class="block text-sm font-medium text-foreground mb-1.5">
                    @php esc_html_e( 'New password', 'woocommerce' ); @endphp
                </label>
                <input type="password"
                       class="woocommerce-Input woocommerce-Input--password input-text block w-full rounded-lg border border-outline px-3.5 py-2.5 text-sm text-foreground shadow-xs placeholder:text-subtle focus:border-ring focus:ring-2 focus:ring-ring/20 focus:outline-hidden"
                       name="password_1" id="password_1" autocomplete="new-password" />
            </div>
            <div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="password_2" class="block text-sm font-medium text-foreground mb-1.5">
                    @php esc_html_e( 'Confirm new password', 'woocommerce' ); @endphp
                </label>
                <input type="password"
                       class="woocommerce-Input woocommerce-Input--password input-text block w-full rounded-lg border border-outline px-3.5 py-2.5 text-sm text-foreground shadow-xs placeholder:text-subtle focus:border-ring focus:ring-2 focus:ring-ring/20 focus:outline-hidden"
                       name="password_2" id="password_2" autocomplete="new-password" />
            </div>
        </div>
    </div>

    @php do_action( 'woocommerce_edit_account_form' ); @endphp

    {{-- Submit --}}
    <div class="mt-6 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3">
        @php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); @endphp
        <button type="submit"
                class="woocommerce-Button button inline-flex items-center justify-center rounded-lg bg-primary px-6 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-primary-hover focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-ring transition-colors"
                name="save_account_details"
                value="@php esc_attr_e( 'Save changes', 'woocommerce' ); @endphp">
            @php esc_html_e( 'Save changes', 'woocommerce' ); @endphp
        </button>
        <input type="hidden" name="action" value="save_account_details" />
    </div>

    @php do_action( 'woocommerce_edit_account_form_end' ); @endphp
</form>

@php do_action( 'woocommerce_after_edit_account_form' ); @endphp
