{{-- *
 * View Order
 *
 * Shows the details of a particular order on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/view-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.0.0
  --}}
{{-- docs.woocommerce.com/document/template-structure/ --}}
@php
	$notes = $order->get_customer_order_notes();
@endphp
<p class="mt-2 text-sm text-gray-500 mb-6">
{{--  translators: 1: order number 2: order date 3: order status  --}}
@php
printf(
	esc_html__(
        'Order #%1$s was placed on %2$s and is currently %3$s.', 'woocommerce' ),
		'<mark class="order-number font-medium text-gray-900 bg-transparent">' . $order->get_order_number() . '</mark>',
		'<mark class="order-date font-medium text-gray-900 bg-transparent">' . wc_format_datetime( $order->get_date_created() ) . '</mark>',
		'<mark class="order-status font-medium text-gray-900 bg-transparent">' . wc_get_order_status_name( $order->get_status() ) . '</mark>'
	);
@endphp
</p>

@if ( $notes )
	<div class="divide-y divide-gray-200 mb-6">
		<div class="pb-4">
			<h2 class="text-lg font-medium text-gray-900">@php esc_html_e( 'Order updates', 'woocommerce' ); @endphp</h2>
		</div>
		<div class="pt-6">
			<div class="flow-root">
				<ol class="woocommerce-OrderUpdates commentlist notes overflow-hidden">
					@foreach ( $notes as $note )
						<li class="woocommerce-OrderUpdate comment note">
							<div class="relative pb-8">
								<span class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200"></span>
								<div class="relative flex items-start space-x-3">
									<div class="relative px-1">
										<div class="h-8 w-8 bg-gray-100 rounded-full ring-8 ring-white flex items-center justify-center">
											<svg class="h-5 w-5 text-gray-400" x-description="Heroicon name: solid/chat-alt" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
												<path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z" clip-rule="evenodd"></path>
											</svg>
										</div>
									</div>
									<div class="woocommerce-OrderUpdate-inner comment_container min-w-0 flex-1 py-1.5">
										<div class="woocommerce-OrderUpdate-text comment-text">
											<p class="woocommerce-OrderUpdate-meta meta mt-0.5 text-sm text-gray-500">
												{!! date_i18n( esc_html__( 'l jS \o\f F Y, h:ia', 'woocommerce' ), strtotime( $note->comment_date ) ) !!}
											</p>
											<div class="woocommerce-OrderUpdate-description description mt-2 text-sm text-gray-700 rte">
												{!! wpautop( wptexturize( $note->comment_content ) ) !!}
											</div>
										</div>
									</div>
								</div>
							</div>
						</li>
					@endforeach
				</ol>
			</div>
		</div>
	</div>
@endif

<div class="max-w-3xl">
	@php do_action( 'woocommerce_view_order', $order_id ); @endphp
</div>
