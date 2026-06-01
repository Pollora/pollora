{{--
 * Cart Page
 *
 * Aligned on the Gutenberg Cart block design: column headers, sale badges,
 * short descriptions, trash icon, collapsible coupon in sidebar.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.1.0
 --}}

@php do_action( 'woocommerce_before_cart' ); @endphp

<div class="lg:grid lg:grid-cols-12 lg:gap-x-12 lg:items-start xl:gap-x-16">
    <form class="woocommerce-cart-form lg:col-span-7" action="{!! esc_url( wc_get_cart_url() ) !!}" method="post">
        @php do_action( 'woocommerce_before_cart_table' ); @endphp

        {{-- Column headers --}}
        <div class="hidden sm:flex items-center justify-between pb-3 border-b border-outline">
            <span class="text-xs font-semibold uppercase tracking-wider text-muted">{{ __( 'Product', 'woocommerce' ) }}</span>
            <span class="text-xs font-semibold uppercase tracking-wider text-muted">{{ __( 'Total', 'woocommerce' ) }}</span>
        </div>

        <ul role="list" class="woocommerce-cart-form__contents divide-y divide-outline">
            @php do_action( 'woocommerce_before_cart_contents' ); @endphp

            @foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item )
                @php
                    $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                    $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
                @endphp

                @if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) )
                    @php
                        $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                        $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
                        $product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );

                        $is_on_sale = $_product->is_on_sale();
                        $regular_price = (float) $_product->get_regular_price();
                        $sale_price = (float) $_product->get_price();
                        $qty = $cart_item['quantity'];
                        $savings = $is_on_sale ? ( $regular_price - $sale_price ) * $qty : 0;
                    @endphp

                    <li class="woocommerce-cart-form__cart-item flex gap-4 sm:gap-6 py-6 {!! esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ) !!}">
                        {{-- Thumbnail --}}
                        <div class="product-thumbnail shrink-0 w-20 h-20 sm:w-24 sm:h-24 rounded-lg overflow-hidden bg-surface-alt [&_img]:w-full [&_img]:h-full [&_img]:object-cover">
                            @if ( ! $product_permalink )
                                {!! $thumbnail !!}
                            @else
                                <a href="{{ esc_url( $product_permalink ) }}" class="block w-full h-full">
                                    {!! $thumbnail !!}
                                </a>
                            @endif
                        </div>

                        {{-- Product info --}}
                        <div class="flex-1 min-w-0 flex flex-col">
                            <div class="flex justify-between gap-4">
                                <div class="min-w-0">
                                    {{-- Product name --}}
                                    <h3 class="text-sm font-medium text-foreground">
                                        @if ( $product_permalink )
                                            <a href="{{ esc_url( $product_permalink ) }}" class="hover:text-primary transition-colors">
                                                {!! wp_kses_post( $product_name ) !!}
                                            </a>
                                        @else
                                            {!! wp_kses_post( $product_name ) !!}
                                        @endif
                                    </h3>

                                    @php do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key ); @endphp

                                    {{-- Price per unit --}}
                                    <div class="mt-0.5 text-xs text-muted">
                                        @if ( $is_on_sale )
                                            <del class="text-subtle">{!! wc_price( $regular_price ) !!}</del>
                                        @endif
                                        {!! wc_price( $sale_price ) !!}
                                    </div>

                                    {{-- Short description --}}
                                    @if ( $_product->get_short_description() )
                                        <p class="mt-1 text-xs text-muted line-clamp-2">{!! wp_kses_post( wp_strip_all_tags( $_product->get_short_description() ) ) !!}</p>
                                    @endif

                                    {{-- Variation / meta data --}}
                                    @if ( wc_get_formatted_cart_item_data( $cart_item ) )
                                        <div class="mt-1 text-xs text-subtle">
                                            {!! wp_strip_all_tags( wc_get_formatted_cart_item_data( $cart_item ) ) !!}
                                        </div>
                                    @endif

                                    {{-- Backorder --}}
                                    @if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) )
                                        {!! wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="mt-1 text-xs italic text-muted">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) ) !!}
                                    @endif
                                </div>

                                {{-- Subtotal + savings --}}
                                <div class="text-right shrink-0">
                                    <div class="product-subtotal text-sm font-semibold text-foreground">
                                        {!! apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $qty ), $cart_item, $cart_item_key ) !!}
                                    </div>
                                    @if ( $is_on_sale && $savings > 0 )
                                        <span class="inline-block mt-1 rounded-full bg-accent px-2 py-0.5 text-[11px] font-semibold text-foreground">
                                            {{ sprintf( __( 'Save %s', 'woocommerce' ), html_entity_decode( wp_strip_all_tags( wc_price( $savings ) ) ) ) }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Quantity + remove --}}
                            <div class="mt-2 flex items-center gap-2">
                                <div class="product-quantity">
                                    @php
                                        if ( $_product->is_sold_individually() ) {
                                            $min_quantity = 1;
                                            $max_quantity = 1;
                                        } else {
                                            $min_quantity = 0;
                                            $max_quantity = $_product->get_max_purchase_quantity();
                                        }

                                        $product_quantity = woocommerce_quantity_input(
                                            [
                                                'input_name'   => "cart[{$cart_item_key}][qty]",
                                                'input_value'  => $cart_item['quantity'],
                                                'max_value'    => $max_quantity,
                                                'min_value'    => $min_quantity,
                                                'product_name' => $product_name,
                                            ],
                                            $_product,
                                            false
                                        );
                                    @endphp
                                    {!! apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ) !!}
                                </div>

                                {{-- Remove button — trash icon next to quantity (matches Gutenberg) --}}
                                <div class="product-remove">
                                    {!! apply_filters('woocommerce_cart_item_remove_link',
                                        sprintf(
                                            '<a href="%s" class="block remove p-1.5 text-subtle hover:text-error transition-colors" role="button" aria-label="%s" data-product_id="%s" data-product_sku="%s">
                                                <svg class="size-4.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                                </svg>
                                            </a>',
                                            esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                            esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $product_name ) ) ),
                                            esc_attr( $product_id ),
                                            esc_attr( $_product->get_sku() )
                                        ),
                                        $cart_item_key
                                    ) !!}
                                </div>
                            </div>
                        </div>
                    </li>
                @endif
            @endforeach

            @php do_action( 'woocommerce_cart_contents' ); @endphp

            <li class="border-b-0 hidden">
                <div class="actions">
                    <input type="submit" name="update_cart" class="sr-only" value="{{ esc_attr__( 'Update cart', 'woocommerce' ) }}" />
                    @php do_action( 'woocommerce_cart_actions' ); @endphp
                    @php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); @endphp
                </div>
            </li>

            @php do_action( 'woocommerce_after_cart_contents' ); @endphp
        </ul>

        @php do_action( 'woocommerce_after_cart_table' ); @endphp
    </form>

    @php do_action( 'woocommerce_before_cart_collaterals' ); @endphp

    <div class="cart-collaterals mt-16 bg-surface rounded-lg px-4 py-6 sm:p-6 lg:p-8 lg:mt-0 lg:col-span-5">
        @php do_action( 'woocommerce_cart_collaterals' ); @endphp
    </div>
</div>

@php do_action( 'woocommerce_after_cart' ); @endphp
