{{-- *
 * External product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/external.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
  --}}
{{-- docs.woocommerce.com/document/template-structure/ --}}
@php



do_action( 'woocommerce_before_add_to_cart_form' ); @endphp

<form class="cart mt-8" action="{!! esc_url( $product_url ) !!}" method="get">
	@php do_action( 'woocommerce_before_add_to_cart_button' ); @endphp

	<button type="submit" class="single_add_to_cart_button button alt w-full bg-primary border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-primary-hover focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-ring transition-colors duration-150">
		{!! esc_html( $button_text ) !!}
		@if ($product->get_price_html())
			<span class="mx-2 opacity-50">—</span>
			<span class="[&_del]:text-white/60 [&_del]:text-sm [&_ins]:no-underline">{!! $product->get_price_html() !!}</span>
		@endif
	</button>

	@php wc_query_string_form_fields( $product_url ); @endphp

	@php do_action( 'woocommerce_after_add_to_cart_button' ); @endphp
</form>

@php do_action( 'woocommerce_after_add_to_cart_form' ); @endphp
