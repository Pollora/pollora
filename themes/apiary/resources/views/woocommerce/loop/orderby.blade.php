{{--
 * Show options for ordering
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package Theme\Apiary\WooCommerce
 * @version 9.7.0
 --}}

<form class="woocommerce-ordering" method="get">
    <div class="grid grid-cols-1">
        <select name="orderby" class="orderby col-start-1 row-start-1 w-full appearance-none rounded-md border border-outline bg-white py-1.5 pr-8 pl-3 text-base text-foreground outline-1 -outline-offset-1 outline-outline focus:outline-2 focus:-outline-offset-2 focus:outline-ring sm:text-sm/6" aria-label="{{ __('Shop order', 'woocommerce') }}">
            @foreach ($catalog_orderby_options as $id => $name)
                <option value="{{ e($id) }}" {{ $orderby == $id ? 'selected' : '' }}>{{ e($name) }}</option>
            @endforeach
        </select>
        <svg class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-muted sm:size-4" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" data-slot="icon">
            <path fill-rule="evenodd" d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"></path>
        </svg>
    </div>
    <input type="hidden" name="paged" value="1" />
    {!! wc_query_string_form_fields( null, array( 'orderby', 'submit', 'paged', 'product-page' ) ) !!}
</form>
