{{-- *
 * Edit address form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-address.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
  --}}
{{-- docs.woocommerce.com/document/template-structure/ --}}
@php
	$page_title = ( 'billing' === $load_address ) ? esc_html__( 'Billing address', 'woocommerce' ) : esc_html__( 'Shipping address', 'woocommerce' );
	do_action( 'woocommerce_before_edit_account_address_form' );
@endphp

@if ( ! $load_address )
	@php wc_get_template( 'myaccount/my-address.php' ); @endphp
@else
	<form method="post">
		<h3 class="text-2xl font-bold text-gray-900">{!! apply_filters( 'woocommerce_my_account_edit_address_title', $page_title, $load_address ) !!}</h3>{{--  @codingStandardsIgnoreLine  --}}
		<div class="woocommerce-address-fields">
			@php do_action( "woocommerce_before_edit_address_form_{$load_address}" ); @endphp
			<div class="woocommerce-address-fields__field-wrapper mt-4 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
				@php
				foreach ( $address as $key => $field ) {
					woocommerce_form_field( $key, $field, wc_get_post_data_by_key( $key, $field['value'] ) );
				}
				@endphp
			</div>
			@php do_action( "woocommerce_after_edit_address_form_{$load_address}" ); @endphp
			<div class="py-6 sm:flex sm:items-center sm:justify-end">
				<button type="submit" class="button w-full bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-indigo-500 sm:ml-6 sm:order-last sm:w-auto" name="save_address" value="@php esc_attr_e( 'Save address', 'woocommerce' ); @endphp">@php esc_html_e( 'Save address', 'woocommerce' ); @endphp</button>
				@php wp_nonce_field( 'woocommerce-edit_address', 'woocommerce-edit-address-nonce' ); @endphp
				<input type="hidden" name="action" value="edit_address" />
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
			</div>
		</div>
	</form>
@endif


@php do_action( 'woocommerce_after_edit_account_address_form' ); @endphp
