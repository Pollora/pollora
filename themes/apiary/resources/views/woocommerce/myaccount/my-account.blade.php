{{--
 * My Account page
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 --}}
@php
    do_action( 'woocommerce_account_navigation' );
@endphp

    <div class="woocommerce-MyAccount-content min-w-0 flex-1 pt-6">
        @php do_action( 'woocommerce_account_content' ); @endphp
    </div>
</div>
