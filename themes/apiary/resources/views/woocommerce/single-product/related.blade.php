{{-- *
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     10.3.0
  --}}
{{-- docs.woocommerce.com/document/template-structure/ --}}
@php
@endphp
@if ( $related_products )
    @php
        // Ensure related product images get loading="lazy"
        if ( function_exists( 'wp_increase_content_media_count' ) ) {
            $content_media_count = wp_increase_content_media_count( 0 );
            if ( $content_media_count < wp_omit_loading_attr_threshold() ) {
                wp_increase_content_media_count( wp_omit_loading_attr_threshold() - $content_media_count );
            }
        }

        do_action('woocommerce_before_related_products', $related_products);
    @endphp
    <section class="related products mt-16 border-t border-outline pt-12 pb-16">
        @php
          $heading = apply_filters( 'woocommerce_product_related_products_heading', __( 'Related products', 'woocommerce' ) );
        @endphp

        @if ( $heading )
            <h2 class="text-xl font-bold text-foreground mb-8">{{ $heading }}</h2>
        @endif
        @php woocommerce_product_loop_start(); @endphp

        @foreach ( $related_products as $related_product )
            @php
                $post_object = get_post( $related_product->get_id() );
                setup_postdata( $GLOBALS['post'] =& $post_object );
                wc_get_template_part( 'content', 'product' );
            @endphp
        @endforeach

        @php woocommerce_product_loop_end(); @endphp
    </section>
    @php
        do_action('woocommerce_after_related_products', $related_products);
    @endphp
@endif

@php
  wp_reset_postdata();
@endphp
