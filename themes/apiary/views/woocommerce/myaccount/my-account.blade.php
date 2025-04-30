{{-- *
 * My Account navigation.
 *
 * @since 2.6.0
  --}}
{{-- *
 * My Account page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https: * @package WooCommerce\Templates
 * @version 3.5.0
  --}}
{{-- docs.woocommerce.com/document/template-structure/ --}}
@php


defined( 'ABSPATH' ) || exit;


do_action( 'woocommerce_account_navigation' ); @endphp

	<div class="woocommerce-MyAccount-content flex flex-col min-w-0 flex-1 overflow-hidden">
		{{-- *
			 * My Account content.
			 *
			 * @since 2.6.0
		--}}
		@php
			do_action( 'woocommerce_account_content' );
		@endphp
	</div>
</div><!-- .account-wrapper -->