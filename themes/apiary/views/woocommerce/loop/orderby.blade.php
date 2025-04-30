{{--
    Show options for ordering Blade Template
    This template is a Blade adaptation of the original WooCommerce template.

    @package     WooCommerce\Templates
    @version     3.6.0
--}}

<form class="woocommerce-ordering" method="get">
    <div class="grid grid-cols-1">
        <select name="orderby" class="orderby col-start-1 row-start-1 w-full appearance-none rounded-md border bg-white py-1.5 pr-8 pl-3 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" aria-label="{{ __('Shop order', 'woocommerce') }}">
            @foreach ($catalog_orderby_options as $id => $name)
                <option value="{{ e($id) }}" {{ $orderby == $id ? 'selected' : '' }}>{{ e($name) }}</option>
            @endforeach
        </select>
        <svg class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" data-slot="icon">
            <path fill-rule="evenodd" d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"></path>
        </svg>
    </div>
    <input type="hidden" name="paged" value="1" />
    {!! wc_query_string_form_fields( null, array( 'orderby', 'submit', 'paged', 'product-page' ) ) !!}
</form>
