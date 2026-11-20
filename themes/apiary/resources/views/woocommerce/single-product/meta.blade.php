{{--
 * Single Product Meta
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/meta.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     9.7.0
  --}}
{{-- docs.woocommerce.com/document/template-structure/ --}}
@php
    global $product;
@endphp
<div class="product_meta mt-8 pt-6 border-t border-outline space-y-1.5 text-xs text-subtle">

    @php do_action( 'woocommerce_product_meta_start' ); @endphp

    @if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( \Automattic\WooCommerce\Enums\ProductType::VARIABLE ) ) )
        <div class="sku_wrapper">
            <span class="font-medium text-muted">{{ __('SKU:', 'woocommerce') }}</span>
            <span class="sku">{!! ( $sku = $product->get_sku() ) ? $sku : esc_html__( 'N/A', 'woocommerce' ) !!}</span>
        </div>
    @endif

    {!! wc_get_product_category_list( $product->get_id(), ', ', '<div class="posted_in"><span class="font-medium text-muted">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'woocommerce' ) . '</span> ', '</div>' ) !!}

    {!! wc_get_product_tag_list( $product->get_id(), ', ', '<div class="tagged_as"><span class="font-medium text-muted">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'woocommerce' ) . '</span> ', '</div>' ) !!}

    @php do_action( 'woocommerce_product_meta_end' ); @endphp

</div>
