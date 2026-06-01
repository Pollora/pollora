{{-- Search overlay --}}
<div x-data="{ panelOpen: false }"
     x-on:panel-open.window="panelOpen = ($event.detail.panel === 'search')"
     x-on:panel-close.window="panelOpen = false"
     @keydown.window.escape="if (panelOpen) { window.dispatchEvent(new CustomEvent('panel-close')); }"
     x-show="panelOpen"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0 -translate-y-2"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 -translate-y-2"
     class="absolute inset-x-0 top-0 z-30 bg-white shadow-lg border-b border-outline"
     style="display: none;"
     @click.away="window.dispatchEvent(new CustomEvent('panel-close'))">
    <div class="max-w-3xl mx-auto px-4 py-5 sm:px-6" x-data="productSearch">
        <form action="{{ home_url('/') }}" method="get" role="search" class="relative">
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-muted pointer-events-none" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input x-ref="searchInput"
                   x-effect="if (panelOpen) $nextTick(() => $refs.searchInput.focus())"
                   type="search"
                   name="s"
                   x-model="query"
                   @input="onInput()"
                   @keydown="onKeydown($event)"
                   @focus="if (results.length) open = true"
                   placeholder="{{ __('Search products...', 'woocommerce') }}"
                   class="w-full pl-12 pr-10 py-3 text-base bg-surface border border-outline rounded-full focus:outline-hidden focus:ring-2 focus:ring-ring placeholder:text-subtle"
                   autocomplete="off"
                   role="combobox"
                   aria-autocomplete="list"
                   aria-expanded="false"
                   :aria-expanded="showResults">
            <input type="hidden" name="post_type" value="product">

            {{-- Loading spinner inside input --}}
            <svg x-show="loading" class="absolute right-4 top-1/2 -translate-y-1/2 animate-spin size-4 text-subtle" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true" style="display: none;">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>

            @include('parts.header.search-results')
        </form>
    </div>
</div>
