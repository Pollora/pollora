{{--
 * Content single product
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package Theme\Apiary\WooCommerce
 * @version 3.6.0
 --}}
@php
    global $product;

    do_action( 'woocommerce_before_single_product' );

    if ( post_password_required() ) {
        echo get_the_password_form();     return;
    }
@endphp


<div id="product-@php the_ID(); @endphp" @php wc_product_class( '', $product ); @endphp>
    <div class="lg:grid lg:grid-cols-2 lg:gap-x-8 xl:grid-cols-12 xl:gap-x-12">
        {{--
             * Hook: woocommerce_before_single_product_summary.
             *
             * @hooked woocommerce_show_product_sale_flash - 10
             * @hooked woocommerce_show_product_images - 20
        --}}
        @php
          do_action( 'woocommerce_before_single_product_summary' );
        @endphp

        <div class="summary entry-summary mt-8 lg:mt-0 xl:col-span-5 xl:col-start-8 lg:sticky lg:top-6 lg:self-start">
            {{--
                 * Hook: woocommerce_single_product_summary.
                 *
                 * @hooked woocommerce_template_single_title - 5
                 * @hooked woocommerce_template_single_rating - 10
                 * @hooked woocommerce_template_single_price - 10
                 * @hooked woocommerce_template_single_excerpt - 20
                 * @hooked woocommerce_template_single_add_to_cart - 30
                 * @hooked woocommerce_template_single_meta - 40
                 * @hooked woocommerce_template_single_sharing - 50
                 * @hooked WC_Structured_Data::generate_product_data() - 60
            --}}
            @php
              do_action( 'woocommerce_single_product_summary' );
            @endphp
        </div>

    </div>

    {{-- Reviews section — full width, below the grid --}}
    @php
        if ( comments_open() ) {
            echo '<section id="reviews" class="mt-16 border-t border-outline pt-12">';
            comments_template();
            echo '</section>';
        }
    @endphp

    {{-- Upsells & related products — full width --}}
    @php
        do_action( 'woocommerce_after_single_product_summary' );
    @endphp

</div>

@php do_action( 'woocommerce_after_single_product' ); @endphp

@include('woocommerce.single-product.sticky-bar')
