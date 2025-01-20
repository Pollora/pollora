@php
    if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
        return;
    }

    global $product;

    $columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
    $post_thumbnail_id = $product->get_image_id();
    $wrapper_classes   = apply_filters(
        'woocommerce_single_product_image_gallery_classes',
        array(
            'woocommerce-product-gallery',
            'woocommerce-product-gallery--' . ( $post_thumbnail_id ? 'with-images' : 'without-images' ),
            'woocommerce-product-gallery--columns-' . absint( $columns ),
            'images',
        )
    );
@endphp
<div class="lg:row-end-1 lg:col-span-4 {!! esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ) !!}"
     data-columns="{!! esc_attr( $columns ) !!}" style="opacity: 0; transition: opacity .25s ease-in-out;">
    <h2 class="sr-only">{{ __('Images', 'apiary') }}</h2>
    <figure class="woocommerce-product-gallery__wrapper relative">
        @php
            do_action('woocommerce_before_single_product_gallery');
        @endphp
        @php
            if ( $post_thumbnail_id ) {
                $html = wc_get_gallery_image_html( $post_thumbnail_id, true );
            } else {
                $html  = '<div class="woocommerce-product-gallery__image--placeholder">';
                $html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
                $html .= '</div>';
            }
        @endphp
        @php
            do_action('woocommerce_after_single_product_gallery');
        @endphp

        <div class="lg:col-span-2 lg:row-span-2 rounded-lg overflow-hidden">
            {!! apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id ) !!}
        </div>
        @php
            do_action( 'woocommerce_product_thumbnails' );
        @endphp
    </figure>
</div>
