{{-- *
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
  --}}
{{--  If checkout registration is disabled and not logged in, the user cannot checkout. --}}
{{-- docs.woocommerce.com/document/template-structure/ --}}
@php


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_checkout_form', $checkout );

if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}

@endphp

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="{!! esc_url( wc_get_checkout_url() ) !!}" enctype="multipart/form-data">
	<div class="lg:grid lg:grid-cols-2 lg:gap-x-12 xl:gap-x-16 mt-5">
		@if ( $checkout->get_checkout_fields() )
			<div>
				@php do_action( 'woocommerce_checkout_before_customer_details' ); @endphp
				<div id="customer_details" class="mb-10">
					@php do_action( 'woocommerce_checkout_billing' ); @endphp
					@php do_action( 'woocommerce_checkout_shipping' ); @endphp
				</div>
				@php do_action( 'woocommerce_checkout_after_customer_details' ); @endphp

				@php do_action( 'woocommerce_checkout_before_shipping_methods' ); @endphp

				@if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() )
					<div class="shipping-methods-wrapper">
						@php do_action( 'woocommerce_review_order_before_shipping' ); @endphp
						{!! wc_cart_totals_shipping_html() !!}
						@php do_action( 'woocommerce_review_order_after_shipping' ); @endphp
					</div>
				@endif

				@php do_action( 'woocommerce_checkout_after_shipping_methods' ); @endphp

			</div>
		@endif


		<div class="mt-10 lg:mt-0">
			@php do_action( 'woocommerce_checkout_before_order_review_heading' ); @endphp

			<h3 id="order_review_heading" class="text-lg font-medium text-gray-900">{{ __( 'Your order', 'woocommerce' ) }}</h3>

			@php do_action( 'woocommerce_checkout_before_order_review' ); @endphp

			<div id="order_review" class="woocommerce-checkout-review-order mt-4 bg-white border border-gray-200 rounded-lg shadow-sm">
				@php do_action( 'woocommerce_checkout_order_review' ); @endphp
			</div>
			@php do_action( 'woocommerce_checkout_after_order_review' ); @endphp
		</div>
	</div>

</form>

@php do_action( 'woocommerce_after_checkout_form', $checkout ); @endphp
