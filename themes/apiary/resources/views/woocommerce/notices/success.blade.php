{{--
 * Show success messages as floating toast notifications
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

@foreach ( $notices as $notice )
	@php $dataAttr = wc_get_notice_data_attr( $notice ); @endphp
	<div class="apiary-toast fixed top-4 left-1/2 -translate-x-1/2 z-50 flex items-center gap-3 max-w-md px-4 py-3 rounded-xl bg-white shadow-lg ring-1 ring-outline/80 text-sm text-foreground [&_a]:font-semibold [&_a]:text-primary [&_a]:no-underline hover:[&_a]:text-primary-hover"
	     role="alert"
	     {!! $dataAttr !!}>
		<svg class="w-5 h-5 shrink-0 text-success" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
		<div class="flex-1">{!! wc_kses_notice( $notice['notice'] ) !!}</div>
		<button class="apiary-toast-close shrink-0 p-1 text-subtle hover:text-foreground transition-colors cursor-pointer" aria-label="{{ __('Dismiss', 'apiary') }}">
			<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
		</button>
	</div>
@endforeach
