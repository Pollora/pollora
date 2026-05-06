{{--
    * Hook: woocommerce_before_single_product.
    *
    * @hooked woocommerce_output_all_notices - 10
--}}
@php
    global $product;

    do_action( 'woocommerce_before_single_product' );

    if ( post_password_required() ) {
        echo get_the_password_form();     return;
    }
@endphp


<div id="product-@php the_ID(); @endphp" @php wc_product_class( '', $product ); @endphp>
    <div class="lg:grid lg:grid-rows-1 lg:grid-cols-7 lg:gap-x-8 lg:gap-y-10 xl:gap-x-16">
        {{--
             * Hook: woocommerce_before_single_product_summary.
             *
             * @hooked woocommerce_show_product_sale_flash - 10
             * @hooked woocommerce_show_product_images - 20
        --}}
        @php
          do_action( 'woocommerce_before_single_product_summary' );
        @endphp

        <div class="summary entry-summary max-w-2xl mx-auto mt-14 sm:mt-16 lg:max-w-none lg:mt-0 lg:row-end-2 lg:row-span-2 lg:col-span-3">
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
        {{--
             * Hook: woocommerce_after_single_product_summary.
             *
             * @hooked woocommerce_output_product_data_tabs - 10
             * @hooked woocommerce_upsell_display - 15
             * @hooked woocommerce_output_related_products - 20
        --}}
        @php
            do_action( 'woocommerce_after_single_product_summary' );
        @endphp
    </div>


</div>

@php do_action( 'woocommerce_after_single_product' ); @endphp