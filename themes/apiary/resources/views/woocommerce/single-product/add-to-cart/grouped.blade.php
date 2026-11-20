{{-- *
 * Grouped product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/grouped.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.2.0
  --}}
@php

global $product, $post;

do_action( 'woocommerce_before_add_to_cart_form' );
@endphp

<form class="cart grouped_form mt-8" action="{!! esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ) !!}" method="post" enctype='multipart/form-data'>
    <table cellspacing="0" class="woocommerce-grouped-product-list group_table w-full">
        <thead class="sr-only">
            <tr>
                <th>{{ __('Quantity', 'woocommerce') }}</th>
                <th>{{ __('Product', 'woocommerce') }}</th>
                <th>{{ __('Price', 'woocommerce') }}</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-outline">
            @php
                $quantites_required      = false;
                $previous_post           = $post;
                $grouped_product_columns = apply_filters(
                    'woocommerce_grouped_product_columns',
                    array(
                        'quantity',
                        'label',
                        'price',
                    ),
                    $product
                );
                $show_add_to_cart_button = false;

                do_action( 'woocommerce_grouped_product_list_before', $grouped_product_columns, $quantites_required, $product );

                foreach ( $grouped_products as $grouped_product_child ) {
                    $post_object        = get_post( $grouped_product_child->get_id() );
                    $quantites_required = $quantites_required || ( $grouped_product_child->is_purchasable() && ! $grouped_product_child->has_options() );
                    $post               = $post_object;
                    setup_postdata( $post );

                    if ( $grouped_product_child->is_in_stock() ) {
                        $show_add_to_cart_button = true;
                    }

                    echo '<tr id="product-' . esc_attr( $grouped_product_child->get_id() ) . '" data-price="' . esc_attr( $grouped_product_child->get_price() ) . '" class="woocommerce-grouped-product-list-item py-4 ' . esc_attr( implode( ' ', wc_get_product_class( '', $grouped_product_child ) ) ) . '">';

                    foreach ( $grouped_product_columns as $column_id ) {
                        do_action( 'woocommerce_grouped_product_list_before_' . $column_id, $grouped_product_child );

                        switch ( $column_id ) {
                            case 'quantity':
                                ob_start();

                                if ( ! $grouped_product_child->is_purchasable() || $grouped_product_child->has_options() || ! $grouped_product_child->is_in_stock() ) {
                                    woocommerce_template_loop_add_to_cart();
                                } elseif ( $grouped_product_child->is_sold_individually() ) {
                                    echo '<input type="checkbox" name="' . esc_attr( 'quantity[' . $grouped_product_child->get_id() . ']' ) . '" value="1" id="' . esc_attr( 'quantity-' . $grouped_product_child->get_id() ) . '" class="wc-grouped-product-add-to-cart-checkbox h-4 w-4 rounded border-outline text-primary focus:ring-ring" />';
                                    echo '<label for="' . esc_attr( 'quantity-' . $grouped_product_child->get_id() ) . '" class="sr-only">' . sprintf( esc_html__( 'Buy one of %s', 'woocommerce' ), $grouped_product_child->get_name() ) . '</label>';
                                } else {
                                    do_action( 'woocommerce_before_add_to_cart_quantity' );

                                    woocommerce_quantity_input(
                                        array(
                                            'input_name'  => 'quantity[' . $grouped_product_child->get_id() . ']',
                                            'input_value' => isset( $_POST['quantity'][ $grouped_product_child->get_id() ] ) ? wc_stock_amount( wc_clean( wp_unslash( $_POST['quantity'][ $grouped_product_child->get_id() ] ) ) ) : '',
                                            'min_value'   => apply_filters( 'woocommerce_quantity_input_min', 0, $grouped_product_child ),
                                            'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $grouped_product_child->get_max_purchase_quantity(), $grouped_product_child ),
                                            'placeholder' => '0',
                                        )
                                    );

                                    do_action( 'woocommerce_after_add_to_cart_quantity' );
                                }

                                $value = ob_get_clean();
                                break;

                            case 'label':
                                $value  = '<label for="product-' . esc_attr( $grouped_product_child->get_id() ) . '">';
                                $value .= $grouped_product_child->is_visible() ? '<a href="' . esc_url( apply_filters( 'woocommerce_grouped_product_list_link', $grouped_product_child->get_permalink(), $grouped_product_child->get_id() ) ) . '" class="text-sm font-medium text-foreground hover:text-primary transition-colors">' . $grouped_product_child->get_name() . '</a>' : '<span class="text-sm font-medium text-foreground">' . $grouped_product_child->get_name() . '</span>';
                                $value .= '</label>';
                                break;

                            case 'price':
                                $value = '<span class="text-sm text-muted">' . $grouped_product_child->get_price_html() . '</span>' . wc_get_stock_html( $grouped_product_child );
                                break;

                            default:
                                $value = '';
                                break;
                        }

                        $td_classes = 'woocommerce-grouped-product-list-item__' . esc_attr( $column_id ) . ' py-4 pr-4';
                        if ( $column_id === 'quantity' ) {
                            $td_classes .= ' w-32';
                        } elseif ( $column_id === 'price' ) {
                            $td_classes .= ' text-right';
                        }

                        echo '<td class="' . $td_classes . '">' . apply_filters( 'woocommerce_grouped_product_list_column_' . $column_id, $value, $grouped_product_child ) . '</td>';

                        do_action( 'woocommerce_grouped_product_list_after_' . $column_id, $grouped_product_child );
                    }

                    echo '</tr>';
                }
                $post = $previous_post;
                setup_postdata( $post );

                do_action( 'woocommerce_grouped_product_list_after', $grouped_product_columns, $quantites_required, $product );
            @endphp
        </tbody>
    </table>

    <input type="hidden" name="add-to-cart" value="{!! esc_attr( $product->get_id() ) !!}" />

    @if ( $quantites_required && $show_add_to_cart_button )
        @php do_action( 'woocommerce_before_add_to_cart_button' ); @endphp

        <button type="submit" class="single_add_to_cart_button grouped-add-to-cart button alt mt-6 w-full bg-primary border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-primary-hover focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-ring transition-colors duration-150">
            {!! esc_html( $product->single_add_to_cart_text() ) !!}
            <span class="grouped-price-separator mx-2 opacity-50 hidden">—</span>
            <span class="grouped-price-total"></span>
        </button>

        @php do_action( 'woocommerce_after_add_to_cart_button' ); @endphp
    @endif
</form>

@php do_action( 'woocommerce_after_add_to_cart_form' ); @endphp
