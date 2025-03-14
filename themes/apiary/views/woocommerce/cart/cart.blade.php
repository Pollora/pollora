{{-- *
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.8.0
  --}}
{{-- docs.woocommerce.com/document/template-structure/ --}}
<header class="entry-header pt-8">
    <h1 class="entry-title text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">{!! Loop::title() !!}</h1>
</header><!-- .entry-header -->

@php
    do_action( 'woocommerce_before_cart' );
@endphp

<div class="mt-4 lg:grid lg:grid-cols-12 lg:gap-x-12 lg:items-start xl:gap-x-16">
    <form class="woocommerce-cart-form lg:col-span-7" action="{!! esc_url( wc_get_cart_url() ) !!}" method="post">
        @php do_action( 'woocommerce_before_cart_table' ); @endphp

            <ul role="list" class="divide-y divide-gray-200">
                @php do_action( 'woocommerce_before_cart_contents' ); @endphp
                @foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item )
                    @php
                        $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                        $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
                    @endphp

                    @if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) )
                        @php
                            $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                        @endphp
                        <li class="woocommerce-cart-form__cart-item flex py-6 sm:py-10 {!! esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ) !!}">
                            <div class="product-thumbnail shrink-0">
                                @php
                                  $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
                                @endphp
                                @if ( ! $product_permalink )
                                    {!! $thumbnail !!}
                                @else
                                    {!! sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ) !!}
                                @endif
                            </div>

                            <div class="ml-4 flex-1 flex flex-col justify-between sm:ml-6">
                                <div class="relative pr-9 sm:grid sm:grid-cols-2 sm:gap-x-6 sm:pr-0">
                                    <div>
                                        <div class="flex justify-between">
                                            <h3 class="product-name text-sm">
                                                {{--  Backorder notification. --}}
                                                {{--  Meta data. --}}

                                                @if ( ! $product_permalink ) {
                                                    {!! wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' ) !!}
                                                @else
                                                    {!! wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s" class="font-medium text-gray-700 hover:text-gray-800">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) ) !!}
                                                @endif
                                                @php
                                                    do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );
                                                @endphp
                                                {!! wc_get_formatted_cart_item_data( $cart_item ) !!}
                                                @if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) )
                                                    {!! wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification prose-sm italic text-gray-800">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) ) !!}
                                                @endif
                                            </h3>


                                        </div>

                                        <div class="product-price mt-1 text-xs text-gray-900" data-title="@php esc_attr_e( 'Price', 'woocommerce' ); @endphp">
                                            {{--  PHPCS: XSS ok. --}}
                                            {!! apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ) !!} x {{ $cart_item['quantity'] }}
                                        </div>
                                        <div class="product-subtotal mt-1 text-sm font-medium text-gray-900" data-title="@php esc_attr_e( 'Subtotal', 'woocommerce' ); @endphp">
                                            {{--  PHPCS: XSS ok. --}}
                                            {!! apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ) !!}
                                        </div>
                                    </div>
                                    <div class="mt-4 sm:mt-0 sm:pr-9">
                                        <div class="product-quantity">
                                            <label for="quantity-0" class="sr-only">{{ __( 'Quantity', 'woocommerce' ) }}</label>
                                            {{--  PHPCS: XSS ok. --}}
                                            @php
                                                if ( $_product->is_sold_individually() ) {
                                                    $product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
                                                } else {
                                                    $product_quantity = woocommerce_quantity_input(
                                                        array(
                                                            'input_name'   => "cart[{$cart_item_key}][qty]",
                                                            'input_value'  => $cart_item['quantity'],
                                                            'max_value'    => $_product->get_max_purchase_quantity(),
                                                            'min_value'    => '0',
                                                            'product_name' => $_product->get_name(),
                                                        ),
                                                        $_product,
                                                        false
                                                    );
                                                }
                                            @endphp
                                            {!! apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ) !!}
                                        </div>

                                        <div class="product-remove absolute top-0 right-0">
                                            {{--  phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped --}}
                                            {!! apply_filters('woocommerce_cart_item_remove_link',
                                                    sprintf(
                                                        '<a href="%s" class="remove -m-2 p-2 inline-flex text-gray-400 hover:text-gray-500" aria-label="%s" data-product_id="%s" data-product_sku="%s">
                                                            <span class="sr-only">Remove</span>
                                                            <svg class="h-5 w-5" x-description="Heroicon name: solid/x" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        </a>',
                                                        esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                                        esc_html__( 'Remove this item', 'woocommerce' ),
                                                        esc_attr( $product_id ),
                                                        esc_attr( $_product->get_sku() )
                                                    ),
                                                    $cart_item_key
                                                ) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endif
                @endforeach

                @if ( wc_coupons_enabled() )
                    <li class="coupon py-6">
                        <label for="coupon_code" class="block text-sm font-medium text-gray-700">@php esc_html_e( 'Coupon:', 'woocommerce' ); @endphp</label>
                        <div class="flex space-x-4 mt-1">
                            <input type="text" name="coupon_code" class="input-text block w-full border-gray-300 rounded-md shadow-xs focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="coupon_code" value="" placeholder="@php esc_attr_e( 'Coupon code', 'woocommerce' ); @endphp" />
                            <button type="submit" class="button bg-gray-200 text-sm font-medium text-gray-600 rounded-md px-4 hover:bg-gray-300 focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-indigo-500" name="apply_coupon" value="@php esc_attr_e( 'Apply coupon', 'woocommerce' ); @endphp" >@php esc_attr_e( 'Apply', 'woocommerce' ); @endphp</button>
                        </div>
                        @php do_action( 'woocommerce_cart_coupon' ); @endphp
                    </li>
                @endif

                @php do_action( 'woocommerce_cart_contents' ); @endphp

                <li class="border-b-0">
                    <div class="py-6 sm:flex sm:items-center sm:justify-end">
                        <button type="submit" class="w-full bg-indigo-600 border border-transparent rounded-md shadow-xs py-2 px-4 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-indigo-500 sm:ml-6 sm:order-last sm:w-auto" name="update_cart" value="@php esc_attr_e( 'Update cart', 'woocommerce' ); @endphp">@php esc_html_e( 'Update cart', 'woocommerce' ); @endphp</button>
                    </div>
                    <div class="actions">
                        @php do_action( 'woocommerce_cart_actions' ); @endphp

                        @php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); @endphp
                    </div>
                </li>

                @php do_action( 'woocommerce_after_cart_contents' ); @endphp
            </ul>

        @php do_action( 'woocommerce_after_cart_table' ); @endphp
    </form>

    @php do_action( 'woocommerce_before_cart_collaterals' ); @endphp

    <div class="cart-collaterals mt-16 bg-gray-50 rounded-lg px-4 py-6 sm:p-6 lg:p-8 lg:mt-4 lg:col-span-5">
        {{-- *
         * Cart collaterals hook.
         *
         * @hooked woocommerce_cross_sell_display
         * @hooked woocommerce_cart_totals - 10
          --}}
        @php
            do_action( 'woocommerce_cart_collaterals' );
        @endphp
    </div>
</div>

@php do_action( 'woocommerce_after_cart' ); @endphp