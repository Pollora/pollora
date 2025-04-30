{{-- *
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.6.0
  --}}
{{-- docs.woocommerce.com/document/template-structure/ --}}
<div class="account-wrapper md:flex mt-4">
	<div class="navigation-wrapper flex flex-col w-64">
		@php
			do_action( 'woocommerce_before_account_navigation' );
		@endphp
		<nav class="woocommerce-MyAccount-navigation">
			<ul class="-mx-2 space-y-1">
				@foreach ( wc_get_account_menu_items() as $endpoint => $label )
					@php
						$item_class = wc_get_account_menu_item_classes( $endpoint );
						$current = strpos($item_class, ' is-active ') !== false;
					@endphp
					<li class="{!! $item_class !!}">
						<a href="{!! esc_url( wc_get_account_endpoint_url( $endpoint ) ) !!}" class="hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ $current ? 'text-indigo-600' : 'text-gray-600' }}">
							{!! esc_html( $label ) !!}
						</a>
					</li>
				@endforeach
			</ul>
		</nav>
		@php do_action( 'woocommerce_after_account_navigation' ); @endphp
	</div>
