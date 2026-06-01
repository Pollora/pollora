{{-- Row 1 — Logo / Search / Actions --}}
<div class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 lg:h-18 gap-4 lg:gap-8">

            {{-- Mobile: hamburger --}}
            @if (has_nav_menu('menu-primary'))
            <button x-data
                    class="lg:hidden -ml-2 p-2 text-foreground hover:text-primary transition-colors focus:outline-2 focus:outline-offset-2 focus:outline-ring"
                    aria-label="{{ __('Toggle menu', 'apiary') }}"
                    aria-controls="mobile-nav"
                    @click="window.dispatchEvent(new CustomEvent('panel-open', { detail: { panel: 'menu' }}))">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                </svg>
            </button>
            @endif

            {{-- Logo --}}
            <a href="{{ home_url() }}" class="shrink-0 text-foreground no-underline focus:outline-2 focus:outline-offset-2 focus:outline-ring">
                @if (has_custom_logo())
                    {!! \Theme\Apiary\custom_logo('h-8 lg:h-9 w-auto', 'custom-logo-link', false) !!}
                @else
                    <span class="text-xl lg:text-2xl font-bold tracking-tight">{{ get_bloginfo('name') }}</span>
                @endif
            </a>

            {{-- Desktop: inline search bar --}}
            <div class="hidden lg:flex flex-1 max-w-lg mx-auto" x-data="productSearch">
                <form action="{{ home_url('/') }}" method="get" role="search" class="relative w-full group">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 h-4.5 w-4.5 text-subtle group-focus-within:text-foreground transition-colors pointer-events-none" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="search"
                           name="s"
                           x-model="query"
                           @input="onInput()"
                           @keydown="onKeydown($event)"
                           @focus="if (results.length) open = true"
                           placeholder="{{ __('Search products...', 'woocommerce') }}"
                           class="w-full pl-10 pr-4 py-2.5 text-sm bg-surface border border-outline rounded-full focus:outline-hidden focus:ring-2 focus:ring-ring focus:bg-white placeholder:text-subtle transition-all"
                           autocomplete="off"
                           role="combobox"
                           aria-autocomplete="list"
                           aria-expanded="false"
                           :aria-expanded="showResults">
                    <input type="hidden" name="post_type" value="product">

                    {{-- Loading spinner inside input --}}
                    <svg x-show="loading" class="absolute right-3.5 top-1/2 -translate-y-1/2 animate-spin size-4 text-subtle" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true" style="display: none;">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>

                    @include('parts.header.search-results')
                </form>
            </div>

            {{-- Right actions --}}
            <div class="flex items-center gap-1 sm:gap-2">
                {{-- Mobile: search toggle --}}
                <button x-data
                        @click="window.dispatchEvent(new CustomEvent('panel-open', { detail: { panel: 'search' }}))"
                        class="lg:hidden p-2 text-muted hover:text-foreground transition-colors">
                    <span class="sr-only">{{ __('Search', 'woocommerce') }}</span>
                    <svg class="w-5.5 h-5.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>

                {{-- My account --}}
                <a href="{{ get_permalink( get_option('woocommerce_myaccount_page_id') ) }}" class="p-2 text-muted hover:text-foreground transition-colors">
                    <span class="sr-only">
                        @if (is_user_logged_in())
                            {{ __('My Account','woocommerce') }}
                        @else
                            {{ __('Login / Register','woocommerce') }}
                        @endif
                    </span>
                    <svg class="w-5.5 h-5.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                    </svg>
                </a>

                {{-- Separator --}}
                <span class="hidden sm:block h-5 w-px bg-outline mx-1" aria-hidden="true"></span>

                {{-- Cart --}}
                <button x-data
                        id="cart-btn"
                        @click="window.dispatchEvent(new CustomEvent('panel-open', { detail: { panel: 'cart' }}))"
                        aria-label="{{ sprintf(__('Cart, %d items', 'apiary'), wc_get_cart_item_count()) }}"
                        class="group relative p-2 text-muted hover:text-foreground transition-colors">
                    <svg class="w-5.5 h-5.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                    </svg>
                    <span class="cart-badge absolute -top-0.5 -right-0.5 items-center justify-center size-[18px] text-[10px] font-bold text-white bg-primary rounded-full {{ wc_get_cart_item_count() > 0 ? 'flex' : 'hidden' }}"><span class="cart-count">{{ wc_get_cart_item_count() }}</span></span>
                </button>
            </div>
        </div>
    </div>
</div>
