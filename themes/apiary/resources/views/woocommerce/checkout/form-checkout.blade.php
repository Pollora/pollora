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
 * @version 9.4.0
  --}}
{{--  If checkout registration is disabled and not logged in, the user cannot checkout. --}}
{{-- docs.woocommerce.com/document/template-structure/ --}}
@php



do_action( 'woocommerce_before_checkout_form', $checkout );

if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}

@endphp

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="{!! esc_url( wc_get_checkout_url() ) !!}" enctype="multipart/form-data" aria-label="@php esc_attr_e( 'Checkout', 'woocommerce' ); @endphp">
	<div class="lg:grid lg:grid-cols-5 lg:gap-x-12 xl:gap-x-16 mt-5 space-y-8 lg:space-y-0">
		{{-- Mobile: Order summary first (collapsible) --}}
		<div class="lg:hidden" x-data="{ summaryOpen: false }">
			@php do_action( 'woocommerce_checkout_before_order_review_heading' ); @endphp
			@php do_action( 'woocommerce_checkout_before_order_review' ); @endphp
			<div id="order_review_mobile" class="woocommerce-checkout-review-order bg-surface lg:border lg:border-outline rounded-xl">
				<button type="button" @click="summaryOpen = !summaryOpen" class="flex items-center justify-between w-full p-5 text-left">
					<h3 class="text-lg font-bold text-foreground m-0">{{ __( 'Order summary', 'woocommerce' ) }}</h3>
					<div class="flex items-center gap-2">
						<span class="text-sm font-semibold text-foreground">{!! WC()->cart->get_total() !!}</span>
						<svg class="size-5 text-muted transition-transform duration-200" :class="{ 'rotate-180': summaryOpen }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
					</div>
				</button>
				<div x-show="summaryOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak class="px-5 pb-5">
					@php do_action( 'woocommerce_checkout_order_review' ); @endphp
				</div>
			</div>
			@php do_action( 'woocommerce_checkout_after_order_review' ); @endphp
		</div>

		@if ( $checkout->get_checkout_fields() )
			<div class="lg:col-span-3">
				@php do_action( 'woocommerce_checkout_before_customer_details' ); @endphp
				<div id="customer_details" class="mb-6 lg:mb-10">
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

				{{-- Payment — rendered in main column (like Gutenberg checkout) --}}
				@php woocommerce_checkout_payment(); @endphp

			</div>
		@endif

		{{-- Desktop: Order summary sidebar (hidden on mobile where collapsible version shows) --}}
		<div class="hidden lg:block lg:col-span-2">
			@php do_action( 'woocommerce_checkout_before_order_review_heading' ); @endphp
			@php do_action( 'woocommerce_checkout_before_order_review' ); @endphp
			<div id="order_review" class="woocommerce-checkout-review-order bg-surface border border-outline rounded-xl p-5 sm:p-6 lg:sticky lg:top-24">
				<h3 id="order_review_heading" class="text-lg font-bold text-foreground mt-0 mb-4">{{ __( 'Order summary', 'woocommerce' ) }}</h3>
				@php do_action( 'woocommerce_checkout_order_review' ); @endphp
			</div>
			@php do_action( 'woocommerce_checkout_after_order_review' ); @endphp
		</div>
	</div>

</form>

@php do_action( 'woocommerce_after_checkout_form', $checkout ); @endphp
