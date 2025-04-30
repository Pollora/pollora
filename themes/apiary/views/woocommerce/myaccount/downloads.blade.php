{{-- *
 * Downloads
 *
 * Shows downloads on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/downloads.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.2.0
  --}}
{{-- docs.woocommerce.com/document/template-structure/ --}}
@php


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$downloads     = WC()->customer->get_downloadable_products();
$has_downloads = (bool) $downloads;

do_action( 'woocommerce_before_account_downloads', $has_downloads ); @endphp


@if ( $has_downloads )
	@php do_action( 'woocommerce_before_available_downloads' ); @endphp
	@php do_action( 'woocommerce_available_downloads', $downloads ); @endphp
	@php do_action( 'woocommerce_after_available_downloads' ); @endphp
@else
	<div class="woocommerce-Message woocommerce-Message--info woocommerce-info lg:flex">
		<div class="py-4 lg:py-2 italic">
			@php esc_html_e( 'No downloads available yet.', 'woocommerce' ); @endphp
		</div>
		<div class="ml-auto lg:pl-4">
			<a class="woocommerce-Button button w-full flex items-center justify-center bg-indigo-600 py-2 px-2.5 border border-transparent rounded-md shadow-xs text-sm font-medium text-white hover:bg-indigo-700 focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:w-full sm:grow-0" href="{!! esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ) !!}">
				@php esc_html_e( 'Browse products', 'woocommerce' ); @endphp
			</a>
		</div>
	</div>

@endif


@php do_action( 'woocommerce_after_account_downloads', $has_downloads ); @endphp
