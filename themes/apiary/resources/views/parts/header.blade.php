{{--
 * Site header with navigation
 *
 * @package Theme\Apiary
 --}}
<div class="bg-white">
    <header class="relative z-10">
        @include('parts.header.overlay')

        <div aria-label="Top">
            @include('parts.header.store-notice')
            @include('parts.header.top-bar')
            @include('parts.header.navigation')
        </div>
    </header>

    @include('parts.header.search-overlay')
    @include('parts.header.mobile-nav')
    @include('parts.header.cart-drawer')

    {{-- Add-to-Cart Confirmation Modal --}}
    @include('woocommerce.parts.modal-add-to-cart')
</div>
