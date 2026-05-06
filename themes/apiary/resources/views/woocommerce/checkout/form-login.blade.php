{{-- *
 * Checkout login form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.8.0
  --}}
{{-- docs.woocommerce.com/document/template-structure/ --}}
@php


defined( 'ABSPATH' ) || exit;

if ( is_user_logged_in() || 'no' === get_option( 'woocommerce_enable_checkout_login_reminder' ) ) {
	return;
}

@endphp

<div class="woocommerce-form-login-toggle">
	<div class="mb-10 border-b border-gray-200 pb-6 sm:flex sm:items-center sm:justify-between">
		<p class="mt-4 text-center text-sm text-gray-500 sm:mt-0 sm:text-left">{{ __( 'Returning customer?', 'woocommerce' ) }}</p>
		<button type="submit" class="w-full rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-xs hover:bg-indigo-700 focus:outline-hidden focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-50 sm:order-last sm:ml-6 sm:w-auto" @click="loginModalOpen = true">{{ __( 'Click here to login', 'woocommerce' ) }}</button>
	</div>
</div>
@php

woocommerce_login_form(
	array(
		'message'  => esc_html__( 'If you have shopped with us before, please enter your details below. If you are a new customer, please proceed to the Billing section.', 'woocommerce' ),
		'redirect' => wc_get_checkout_url(),
		'hidden'   => true,
	)
);
@endphp
