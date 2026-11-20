{{--
 * Header cart count
 *
 * Rendered with the page, and again as a WooCommerce fragment after every
 * add to cart, so its visibility is decided here, in one place.
 *
 * @package Theme\Apiary
 --}}
@php($count = wc_get_cart_item_count())
<span class="cart-badge absolute -top-0.5 -right-0.5 items-center justify-center size-[18px] text-[10px] font-bold text-white bg-primary rounded-full {{ $count > 0 ? 'flex' : 'hidden' }}"><span class="cart-count">{{ $count }}</span></span>
