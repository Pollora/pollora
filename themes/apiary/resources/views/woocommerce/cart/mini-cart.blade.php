{{--
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.0.0
--}}

@php do_action( 'woocommerce_before_mini_cart' ); @endphp

{{-- Header — fixed at top --}}
<div class="shrink-0 flex items-center justify-between px-4 sm:px-6 py-4 border-b border-outline">
    <h2 class="text-lg font-semibold text-foreground" id="slide-over-title">
        <a href="{{ esc_url( wc_get_cart_url() ) }}" class="no-underline">{{ __('Cart', 'woocommerce') }}</a>
        @if ( WC()->cart && ! WC()->cart->is_empty() )
            <span class="ml-1.5 text-sm font-normal text-muted">({{ WC()->cart->get_cart_contents_count() }})</span>
        @endif
    </h2>
    <button type="button" class="-m-2 p-2 text-subtle hover:text-foreground transition-colors" @click="window.dispatchEvent(new CustomEvent('panel-close'))">
        <span class="sr-only">{{ __('Close panel', 'apiary') }}</span>
        <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
        </svg>
    </button>
</div>

{{-- Product list — scrollable middle zone --}}
<div class="flex-1 overflow-y-auto px-4 sm:px-6">
    @if ( WC()->cart && ! WC()->cart->is_empty() )
        <ul role="list" class="woocommerce-mini-cart divide-y divide-outline {!! esc_attr( $args['list_class'] ) !!}">
            @php do_action( 'woocommerce_before_mini_cart_contents' ); @endphp

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
                    <li class="woocommerce-mini-cart-item py-4 flex gap-4 {!! esc_attr( apply_filters( 'woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key ) ) !!}">
                        <div class="shrink-0 size-20 rounded-lg overflow-hidden bg-surface-alt [&_img]:size-full [&_img]:object-cover [&_img]:m-0">
                            @if ( empty( $product_permalink ) )
                                {!! $thumbnail !!}
                            @else
                                <a href="{{ esc_url( $product_permalink ) }}" class="block size-full">
                                    {!! $thumbnail !!}
                                    <span class="sr-only">{!! wp_kses_post( $product_name ) !!}</span>
                                </a>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0 flex flex-col">
                            <div class="flex justify-between gap-2">
                                <h3 class="text-sm font-medium text-foreground leading-snug">
                                    <a href="{{ esc_url( $product_permalink ) }}" class="hover:text-primary transition-colors">
                                        {!! wp_kses_post( $product_name ) !!}
                                    </a>
                                </h3>
                                <p class="text-sm font-semibold text-foreground shrink-0">
                                    {!! $product_price !!}
                                </p>
                            </div>
                            @if ( wc_get_formatted_cart_item_data( $cart_item ) )
                                <div class="mt-0.5 text-xs text-muted">
                                    {!! wc_get_formatted_cart_item_data( $cart_item ) !!}
                                </div>
                            @endif
                            <div class="mt-auto pt-2 flex items-center justify-between">
                                <span class="text-xs text-muted">
                                    {!! apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ) !!}
                                </span>
                                @php
                                    $remove_success_message = sprintf( __( '&ldquo;%s&rdquo; has been removed from your cart.', 'woocommerce' ), wp_strip_all_tags( $product_name ) );
                                @endphp
                                {!!
                                    apply_filters('woocommerce_cart_item_remove_link',
                                        sprintf(
                                            '<a href="%s" class="text-xs font-medium text-subtle hover:text-error transition-colors remove_from_cart_button" role="button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s" data-success_message="%s">%s</a>',
                                            esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                            /* translators: %s: product name */
                                            esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $product_name ) ) ),
                                            esc_attr( $product_id ),
                                            esc_attr( $cart_item_key ),
                                            esc_attr( $_product->get_sku() ),
                                            esc_attr( $remove_success_message ),
                                            esc_html__( 'Remove', 'woocommerce' ),
                                        ),
                                        $cart_item_key
                                   )
                                !!}
                            </div>
                        </div>
                    </li>
                @endif
            @endforeach

            @php do_action( 'woocommerce_mini_cart_contents' ); @endphp
        </ul>
    @else
        <div class="woocommerce-mini-cart__empty-message flex flex-col items-center justify-center py-16">
            <svg class="size-12 text-subtle" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
            </svg>
            <p class="mt-4 text-sm font-medium text-foreground">{{ esc_html__( 'Your cart is empty', 'woocommerce' ) }}</p>
            <button type="button" class="mt-3 text-sm font-medium text-primary hover:text-primary-hover transition-colors" @click="window.dispatchEvent(new CustomEvent('panel-close'))">
                {{ __('Continue shopping', 'woocommerce') }} <span aria-hidden="true">&rarr;</span>
            </button>
        </div>
    @endif
</div>

{{-- Footer — fixed at bottom --}}
@if ( WC()->cart && ! WC()->cart->is_empty() )
    <div class="shrink-0 border-t border-outline bg-white px-4 sm:px-6 py-5">
        <div class="flex items-center justify-between">
            <span class="text-sm font-medium text-foreground">{{ esc_html__( 'Subtotal', 'woocommerce' ) }}</span>
            <span class="text-base font-semibold text-foreground">{!! WC()->cart->get_cart_subtotal() !!}</span>
        </div>
        @php do_action( 'woocommerce_widget_shopping_cart_total' ); @endphp

        <p class="mt-1 text-xs text-muted">{{ __( 'Shipping & taxes calculated at checkout.', 'woocommerce' ) }}</p>

        @php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); @endphp

        <div class="woocommerce-mini-cart__buttons buttons mt-4 space-y-3">
            @php do_action( 'woocommerce_widget_shopping_cart_buttons' ); @endphp
        </div>

        @php do_action( 'woocommerce_widget_shopping_cart_after_buttons' ); @endphp
    </div>
@endif

@php do_action( 'woocommerce_after_mini_cart' ); @endphp
