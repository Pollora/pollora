{{-- *
 * Product attributes
 *
 * Used by list_attributes() in the products class.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-attributes.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.3.0
  --}}
{{-- docs.woocommerce.com/document/template-structure/ --}}
@php
    if ( ! $product_attributes ) {
        return;
    }
@endphp
<div>
    <table class="woocommerce-product-attributes shop_attributes min-w-full divide-y divide-outline" aria-label="@php esc_attr_e( 'Product Details', 'woocommerce' ); @endphp">
        <tbody class="bg-white divide-y divide-outline">
            @foreach ( $product_attributes as $product_attribute_key => $product_attribute )
                <tr class="woocommerce-product-attributes-item woocommerce-product-attributes-item--{!! esc_attr( $product_attribute_key ) !!}">
                    <th scope="row" class="woocommerce-product-attributes-item__label px-6 py-4 whitespace-nowrap text-sm text-left font-medium text-foreground">{!! wp_kses_post( $product_attribute['label'] ) !!}</th>
                    <td class="woocommerce-product-attributes-item__value px-6 py-4 whitespace-nowrap text-sm text-left text-foreground">{!! wp_kses_post( $product_attribute['value'] ) !!}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>