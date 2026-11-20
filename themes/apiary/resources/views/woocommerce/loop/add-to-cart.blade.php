{{--
 * Add to cart button in product loop
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package Theme\Apiary\WooCommerce
 * @version 9.2.0
 --}}
@php
    global $product;
    $args['class'] .= ' relative flex bg-primary border border-transparent rounded-md py-2.5 px-3 sm:px-8 items-center justify-center text-xs sm:text-sm font-medium text-white no-underline hover:bg-primary-hover transition-colors duration-150';
@endphp

{!! apply_filters(
    'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
    sprintf(
        '<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
        esc_url( $product->add_to_cart_url() ),
        esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
        esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
        isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
        esc_html( $product->add_to_cart_text() )
    ),
    $product,
    $args
) !!}