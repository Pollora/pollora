{{-- *
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
  --}}
{{--  Exit if accessed directly. --}}
{{-- docs.woocommerce.com/document/template-structure/ --}}
@php
	$allowed_html = [
		'a' => [
			'href' => [],
		],
	];
@endphp

<p class="text-sm font-medium text-gray-500">
	{{--  translators: 1: user display name 2: logout url  --}}
	@php
		printf(

			wp_kses( __( 'Hello %1$s (not %1$s? <a href="%2$s">Log out</a>)', 'woocommerce' ), $allowed_html ),
			'<strong class="text-gray-900">' . esc_html( $current_user->display_name ) . '</strong>',
			esc_url( wc_logout_url() )
		);
	@endphp
</p>

<p class="text-sm font-medium text-gray-500 mt-4">
	{{--  translators: 1: Orders URL 2: Addresses URL 3: Account URL.  --}}
	{{--  translators: 1: Orders URL 2: Address URL 3: Account URL.  --}}
	@php
		$dashboard_desc = __( 'From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">billing address</a>, and <a href="%3$s">edit your password and account details</a>.', 'woocommerce' );
		if ( wc_shipping_enabled() ) {

			$dashboard_desc = __( 'From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">shipping and billing addresses</a>, and <a href="%3$s">edit your password and account details</a>.', 'woocommerce' );
		}
		printf(
			wp_kses( $dashboard_desc, $allowed_html ),
			esc_url( wc_get_endpoint_url( 'orders' ) ),
			esc_url( wc_get_endpoint_url( 'edit-address' ) ),
			esc_url( wc_get_endpoint_url( 'edit-account' ) )
		);
	@endphp
</p>

{{--  Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues.  --}}
{{-- *
	 * Deprecated woocommerce_after_my_account action.
	 *
	 * @deprecated 2.6.0
	  --}}
{{-- *
	 * Deprecated woocommerce_before_my_account action.
	 *
	 * @deprecated 2.6.0
	  --}}
{{-- *
	 * My Account dashboard.
	 *
	 * @since 2.6.0
	  --}}
@php
	do_action( 'woocommerce_account_dashboard' );
	do_action( 'woocommerce_before_my_account' );
	do_action( 'woocommerce_after_my_account' );
@endphp
