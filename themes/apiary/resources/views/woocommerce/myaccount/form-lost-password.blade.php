{{-- *
 * Lost password form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-lost-password.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.2
  --}}
{{-- docs.woocommerce.com/document/template-structure/ --}}
@php


defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_lost_password_form' );
@endphp

<div class="u-columns col2-set lg:grid lg:grid-cols-12 lg:auto-rows-min lg:gap-x-14 lg:min-h-full" id="customer_login">
    <div class="u-column1 col-1 lg:col-start-1 lg:col-span-6">

        <form method="post" class="woocommerce-ResetPassword lost_reset_password space-y-6 mt-6 mx-auto">

            <p>{!! apply_filters( 'woocommerce_lost_password_message', esc_html__( 'Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.', 'woocommerce' ) ) !!}</p>{{--  @codingStandardsIgnoreLine  --}}


            <div class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
                <label for="user_login" class="block text-sm font-medium text-gray-700">@php esc_html_e( 'Username or email', 'woocommerce' ); @endphp</label>
                <div class="mt-1">
                    <input class="woocommerce-Input woocommerce-Input--text input-text border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-xs focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" type="text" name="user_login" id="user_login" autocomplete="username" />
                </div>
            </div>

            <div class="clear"></div>

            @php do_action( 'woocommerce_lostpassword_form' ); @endphp

            <div class="woocommerce-form-row form-row">
                <input type="hidden" name="wc_reset_password" value="true" />
                <button type="submit" class="woocommerce-Button button w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-xs text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" value="@php esc_attr_e( 'Reset password', 'woocommerce' ); @endphp">@php esc_html_e( 'Reset password', 'woocommerce' ); @endphp</button>
            </div>

            @php wp_nonce_field( 'lost_password', 'woocommerce-lost-password-nonce' ); @endphp

        </form>
    </div>
</div>
@php
do_action( 'woocommerce_after_lost_password_form' );
@endphp
