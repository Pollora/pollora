{{-- *
 * My Addresses
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-address.php.
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
@php
	$customer_id = get_current_user_id();

	if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) {
		$get_addresses = apply_filters(
			'woocommerce_my_account_get_addresses',
			array(
				'billing'  => __( 'Billing address', 'woocommerce' ),
				'shipping' => __( 'Shipping address', 'woocommerce' ),
			),
			$customer_id
		);
	} else {
		$get_addresses = apply_filters(
			'woocommerce_my_account_get_addresses',
			array(
				'billing' => __( 'Billing address', 'woocommerce' ),
			),
			$customer_id
		);
	}

	$oldcol = 1;
	$col    = 1;
@endphp

<p class="text-sm font-medium text-gray-500 mt-4">
	{!! apply_filters( 'woocommerce_my_account_my_address_description', esc_html__( 'The following addresses will be used on the checkout page by default.', 'woocommerce' ) ) !!}
</p>

@if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() )
	<div class="grid grid-cols-{{ count($get_addresses) }} gap-x-6 text-sm py-10 woocommerce-Addresses col2-set addresses">
@endif

@foreach ( $get_addresses as $name => $address_title )
	@php
		$address = wc_get_account_formatted_address( $name );
		$col     = $col * -1;
		$oldcol  = $oldcol * -1;
	@endphp
	<div class="u-column{!! $col < 0 ? 1 : 2 !!} col-{!! $oldcol < 0 ? 1 : 2 !!} woocommerce-Address">
		<header class="woocommerce-Address-title title lg:flex md:items-center md:justify-between md:space-x-4 xl:border-b xl:pb-4">
			<div>
				<h3 class="woocommerce-column__title text-lg font-bold text-gray-900">{!! esc_html( $address_title ) !!}</h3>
			</div>
			<div class="mt-4 flex space-x-3 md:mt-0">
				<a href="{!! esc_url( wc_get_endpoint_url( 'edit-address', $name ) ) !!}" class="edit inline-flex justify-center px-4 py-2 border border-gray-300 shadow-xs text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-gray-900">{!! $address ? esc_html__( 'Edit', 'woocommerce' ) : esc_html__( 'Add', 'woocommerce' ) !!}</a>
			</div>
		</header>
		<address class="mt-2 text-gray-700 not-italic py-4">
			{!! $address ? wp_kses_post( $address ) : esc_html_e( 'You have not set up this type of address yet.', 'woocommerce' ) !!}
		</address>
	</div>


@endforeach



@if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() )

	</div>
	
@endif

