<aside class="product-facets">
    <h2 class="sr-only">Filters</h2>

    <button type="button" x-description="Mobile filter dialog toggle, controls the 'mobileFilterDialogOpen' state." class="inline-flex items-center lg:hidden" @click="open = true">
        <span class="text-sm font-medium text-gray-700">Filters</span>
        <svg class="shrink-0 ml-1 h-5 w-5 text-gray-400" x-description="Heroicon name: solid/plus-sm" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
        </svg>
    </button>

    <div class="hidden lg:block">
        <div class="divide-y divide-gray-200 space-y-10">
            {!! !dynamic_sidebar('shop-sidebar') !!}
        </div>
    </div>
</aside>
