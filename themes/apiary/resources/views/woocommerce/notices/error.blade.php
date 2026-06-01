{{--
 * Show error messages
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.6.0
 --}}
@php
if ( ! $notices ) {
	return;
}
@endphp

<div class="woocommerce-error flex items-start gap-3 rounded-lg bg-error-light border border-error/20 p-4 my-5 text-sm text-error" role="alert">
	<svg class="w-5 h-5 shrink-0 text-error mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
	<ul class="list-none pl-0 space-y-1">
		@foreach ( $notices as $notice )
			<li{!! wc_get_notice_data_attr( $notice ) !!}>{!! wc_kses_notice( $notice['notice'] ) !!}</li>
		@endforeach
	</ul>
</div>
