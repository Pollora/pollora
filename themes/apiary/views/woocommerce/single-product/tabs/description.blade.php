{{-- *
 * Description tab
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/description.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.0.0
  --}}
{{-- docs.woocommerce.com/document/template-structure/ --}}
@php
	global $post;
	$heading = apply_filters( 'woocommerce_product_description_heading', __( 'Description', 'woocommerce' ) );
@endphp

@if ( $heading )
	<h2 class="sr-only">{!! $heading !!}</h2>
@endif
<div class="py-10">
	@php the_content(); @endphp
</div>
