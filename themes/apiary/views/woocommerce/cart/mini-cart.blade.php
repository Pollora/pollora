{{--
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
--}}
{{-- docs.woocommerce.com/document/template-structure/ --}}
@php do_action( 'woocommerce_before_mini_cart' ); @endphp
<div class="flex-1 py-6 overflow-y-auto px-4 sm:px-6">
    <div class="flex items-start justify-between">
        <h2 class="text-lg font-medium text-gray-900" id="slide-over-title">
            <a href="{{ esc_url( wc_get_cart_url() ) }}">{{ __('Cart', 'woocommerce') }}</a>
        </h2>
        <div class="ml-3 h-7 flex items-center">
            <button type="button" class="-m-2 p-2 text-gray-400 hover:text-gray-500" @click="open = false">
                <span class="sr-only">Close panel</span>
                <svg class="h-6 w-6" x-description="Heroicon name: outline/x" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
    <div class="mt-8">
        @if ( ! WC()->cart->is_empty() )
            <div class="flow-root">
                <ul role="list" class="woocommerce-mini-cart -my-6 divide-y divide-gray-200 {!! esc_attr( $args['list_class'] ) !!}">
                    @php
                      do_action( 'woocommerce_before_mini_cart_contents' );
                    @endphp

                    @foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item )
                        @php
                            $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                            $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
                        @endphp
                        @if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) )
                            @php
                                $product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
                                $thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
                                $product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
                                $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                            @endphp
                            <li class="woocommerce-mini-cart-item py-6 flex {!! esc_attr( apply_filters( 'woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key ) ) !!}">
                                <div class="shrink-0 w-24 h-24 border border-gray-200 rounded-md overflow-hidden">
                                    @if ( empty( $product_permalink ) )
                                        {!! $thumbnail !!}
                                    @else
                                        <a href="{!! esc_url( $product_permalink ) !!}">
                                            {!! $thumbnail . wp_kses_post( $product_name ) !!}
                                        </a>
                                    @endif
                                </div>
                                <div class="ml-4 flex-1 flex flex-col">
                                    <div>
                                        <div class="flex justify-between text-base font-medium text-gray-900">
                                            <h3>
                                                <a href="{!! esc_url( $product_permalink ) !!}">
                                                    {{ wp_kses_post( $product_name ) }}
                                                </a>
                                            </h3>
                                            <p class="ml-4">
                                                {!! $product_price !!}
                                            </p>
                                        </div>
                                        <div class="mt-1 text-sm text-gray-500">
                                            {!! wc_get_formatted_cart_item_data( $cart_item ) !!}
                                        </div>
                                        <div class="flex-1 flex items-end justify-between text-sm">
                                            <p class="text-gray-500">
                                                {{ esc_html_e( 'Qty', 'woocommerce' ) }} {!! apply_filters( 'woocommerce_widget_cart_item_quantity', $cart_item['quantity'], $cart_item, $cart_item_key ) !!}
                                            </p>

                                            <div class="flex">
                                                {!!
                                                    apply_filters('woocommerce_cart_item_remove_link',
                                                        sprintf(
                                                            '<a href="%s" class="font-medium text-indigo-600 hover:text-indigo-500 remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">%s</a>',
                                                            esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                                            esc_attr__( 'Remove this item', 'woocommerce' ),
                                                            esc_attr( $product_id ),
                                                            esc_attr( $cart_item_key ),
                                                            esc_attr( $_product->get_sku() ),
                                                            esc_attr__( 'Remove', 'woocommerce' ),
                                                        ),
                                                        $cart_item_key
                                                   )
                                                !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endif
                    @endforeach
                    @php
                      do_action( 'woocommerce_mini_cart_contents' );
                    @endphp
                </ul>
            </div>
        @else
            <p class="woocommerce-mini-cart__empty-message">{{ esc_html( 'No products in the cart.', 'woocommerce' ) }}</p>
        @endif
    </div>
</div>
@if ( ! WC()->cart->is_empty() )
    <div class="border-t border-gray-200 py-6 px-4 sm:px-6">
        <div class="flex justify-between text-base font-medium text-gray-900">
            <p>{!! esc_html__( 'Subtotal', 'woocommerce' ) !!}</p>
            <p>{!! WC()->cart->get_cart_subtotal() !!}</p>
            {{-- *
             * Hook: woocommerce_widget_shopping_cart_total.
             *
             * @hooked woocommerce_widget_shopping_cart_subtotal - 10
              --}}
            @php
                do_action( 'woocommerce_widget_shopping_cart_total' );
            @endphp
        </div>
        <p class="mt-0.5 text-sm text-gray-500">{{ __( 'Shipping costs are calculated during checkout.', 'woocommerce' ) }}</p>
        @php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); @endphp
        <div class="mt-6">
            <a href="{{ esc_url( wc_get_checkout_url() ) }}" class="flex justify-center items-center px-6 py-3 border border-transparent rounded-md shadow-xs text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700">{{ esc_html__( 'Checkout', 'woocommerce' ) }}</a>
        </div>
        <div class="mt-6 flex justify-center text-sm text-center text-gray-500">
            <p>
                {{ __('or', 'apiary') }} <button type="button" class="text-indigo-600 font-medium hover:text-indigo-500" @click="open = false">{{  esc_html__( 'Continue shopping', 'woocommerce' ) }}<span aria-hidden="true"> →</span></button>
            </p>
        </div>
        @php do_action( 'woocommerce_widget_shopping_cart_after_buttons' ); @endphp
    </div>
@endif

@php do_action( 'woocommerce_after_mini_cart' ); @endphp
