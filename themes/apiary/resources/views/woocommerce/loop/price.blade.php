{{--
/**
 * Loop Price
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */
--}}
@php
  global $product;
@endphp

@if ( $price_html = $product->get_price_html() )
    <span class="mt-1 text-sm text-foreground price">{!! str_replace(['<del>', '<ins>'], ['<del class="text-subtle font-normal">', '<ins class="font-semibold no-underline">'], $price_html) !!}</span>
@endif