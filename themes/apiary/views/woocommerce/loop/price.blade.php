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
    <span class="mt-1 text-lg text-gray-900 price">{!! str_replace('<ins>', '<ins class="font-medium">', $price_html) !!}</span>
@endif