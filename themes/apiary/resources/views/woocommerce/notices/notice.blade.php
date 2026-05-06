{{-- *
 * Show messages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/notices/notice.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.9.0
  --}}
{{-- docs.woocommerce.com/document/template-structure/ --}}
@php
	if ( ! $notices ) {
		return;
	}
@endphp


@foreach ( $notices as $notice )
	<div class="woocommerce-info bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 my-5 text-sm"{!! wc_get_notice_data_attr( $notice ) !!}>
		{!! wc_kses_notice( $notice['notice'] ) !!}
	</div>
@endforeach

