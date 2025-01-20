{{-- *
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
  --}}
{{--  Ensure visibility. --}}
{{-- docs.woocommerce.com/document/template-structure/ --}}
@php
    defined( 'ABSPATH' ) || exit;

    global $product;

    if ( empty( $product ) || ! $product->is_visible() ) {
        return;
    }
@endphp
<li {{ wc_product_class( '', $product ) }}>
    <div class="relative flex flex-col h-full justify-between">
        {{-- *
         * Hook: woocommerce_after_shop_loop_item.
         *
         * @hooked woocommerce_template_loop_product_link_close - 5
         * @hooked woocommerce_template_loop_add_to_cart - 10
          --}}
        {{-- *
         * Hook: woocommerce_after_shop_loop_item_title.
         *
         * @hooked woocommerce_template_loop_rating - 5
         * @hooked woocommerce_template_loop_price - 10
          --}}
        {{-- *
         * Hook: woocommerce_shop_loop_item_title.
         *
         * @hooked woocommerce_template_loop_product_title - 10
          --}}
        {{-- *
         * Hook: woocommerce_before_shop_loop_item_title.
         *
         * @hooked woocommerce_show_product_loop_sale_flash - 10
         * @hooked woocommerce_template_loop_product_thumbnail - 10
          --}}
        {{-- *
         * Hook: woocommerce_before_shop_loop_item.
         *
         * @hooked woocommerce_template_loop_product_link_open - 10
          --}}
        @php
            do_action( 'woocommerce_before_shop_loop_item' );
            do_action( 'woocommerce_before_shop_loop_item_title' );
        @endphp
        <div class="flex-1 flex-grow py-4 space-y-2 flex flex-col justify-between">
            <div>
                @php
                    do_action( 'woocommerce_shop_loop_item_title' );
                    do_action( 'woocommerce_after_shop_loop_item_title' );
                 @endphp
            </div>
        </div>
        @php
            do_action( 'woocommerce_after_shop_loop_item' );
        @endphp
    </div>
</li>
