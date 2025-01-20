{{-- *
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.7.0
  --}}
{{-- docs.woocommerce.com/document/template-structure/ --}}
<div class="woocommerce-order max-w-3xl mx-auto">
	<div class="px-4 pt-14 sm:px-6 sm:pt-22 lg:px-8 lg:pt-30">
		@if ( $order )
			@php
				do_action( 'woocommerce_before_thankyou', $order->get_id() );
			@endphp

			@if ( $order->has_status( 'failed' ) )
				<div class="my-10 bg-gray-50 rounded-lg py-6 px-6 text-sm sm:flex items-center">
					<div class="pt-1.5 min-w-0 flex-1 sm:pt-0">
						<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed">
							{{ __( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ) }}
						</p>
					</div>
					<div class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions mt-6 space-y-4 sm:mt-0 sm:ml-6 sm:flex-none sm:w-40">
						<a href="{!! esc_url( $order->get_checkout_payment_url() ) !!}" class="button pay w-full flex items-center justify-center bg-indigo-600 py-2 px-2.5 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:w-full sm:flex-grow-0">
							{{ __( 'Pay', 'woocommerce' ) }}
						</a>
						@if ( is_user_logged_in() )
							<a href="{!! esc_url( wc_get_page_permalink( 'myaccount' ) ) !!}" class="button pay w-full flex items-center justify-center bg-white py-2 px-2.5 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:w-full sm:flex-grow-0">
								{{ __( 'My account', 'woocommerce' ) }}
							</a>
						@endif
					</div>
				</div>

			@else
				<header class="entry-header">
					<h1 class="entry-title text-sm font-semibold uppercase tracking-wide text-indigo-600">{!! the_title() !!}</h1>
				</header><!-- .entry-header -->
				<div class="entry-content">
					{!! wp_link_pages([
						'before' => '<div class="page-links">'.esc_html__('Pages:', 'apiary'),
						'after' => '</div>',
						'echo' => false
					]) !!}
				</div><!-- .entry-co ntent -->
				<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received mt-2 text-3xl font-extrabold tracking-tight sm:text-4xl">
					{!! apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'woocommerce' ), $order ) !!}
				</p>

				<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details my-5">
					<li class="woocommerce-order-overview__order order">
						@php esc_html_e( 'Order number:', 'woocommerce' ); @endphp
						<strong>{!! $order->get_order_number() !!}</strong>
					</li>
					<li class="woocommerce-order-overview__date date">
						@php esc_html_e( 'Date:', 'woocommerce' ); @endphp
						<strong>{!! wc_format_datetime( $order->get_date_created() ) !!}</strong>
					</li>
					@if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() )
						<li class="woocommerce-order-overview__email email">
							@php esc_html_e( 'Email:', 'woocommerce' ); @endphp
							<strong>{!! $order->get_billing_email() !!}</strong>
						</li>
					@endif
					<li class="woocommerce-order-overview__total total">
						@php esc_html_e( 'Total:', 'woocommerce' ); @endphp
						<strong>{!! $order->get_formatted_order_total() !!}</strong>
					</li>
					@if ( $order->get_payment_method_title() )
						<li class="woocommerce-order-overview__payment-method method">
							@php esc_html_e( 'Payment method:', 'woocommerce' ); @endphp
							<strong>{!! wp_kses_post( $order->get_payment_method_title() ) !!}</strong>
						</li>
					@endif
				</ul>
			@endif

			<div class="mt-3 mb-5 text-sm text-gray-500">
				@php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); @endphp
			</div>
			@php do_action( 'woocommerce_thankyou', $order->get_id() ); @endphp

		@else
			<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">
				{!! apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'woocommerce' ), null ) !!}
			</p>
		@endif
	</div>
</div>
