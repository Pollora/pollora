{{-- Mobile slide-out navigation --}}
@php
use \Theme\Apiary\Walkers\MenuPrimary;
@endphp
@if (has_nav_menu('menu-primary'))
<div id="mobile-nav"
     x-data="{ open: false }"
     x-on:panel-open.window="open = ($event.detail.panel === 'menu')"
     x-on:panel-close.window="open = false"
     @keydown.window.escape="if (open) { window.dispatchEvent(new CustomEvent('panel-close')); }"
     x-show="open"
     x-transition:enter="transform transition ease-in-out duration-300"
     x-transition:enter-start="-translate-x-full"
     x-transition:enter-end="translate-x-0"
     x-transition:leave="transform transition ease-in-out duration-300"
     x-transition:leave-start="translate-x-0"
     x-transition:leave-end="-translate-x-full"
     class="fixed inset-y-0 left-0 z-50 w-full max-w-xs bg-white shadow-xl overflow-y-auto lg:hidden"
     style="display: none;"
     aria-label="{{ __('Primary navigation', 'apiary') }}">

    {{-- Mobile nav header --}}
    <div class="flex items-center justify-between h-16 px-4 border-b border-outline">
        <a href="{{ home_url() }}" class="text-foreground no-underline">
            @if (has_custom_logo())
                {!! \Theme\Apiary\custom_logo('h-7 w-auto', 'custom-logo-link', false) !!}
            @else
                <span class="text-lg font-bold tracking-tight">{{ get_bloginfo('name') }}</span>
            @endif
        </a>
        <button @click="window.dispatchEvent(new CustomEvent('panel-close'))" class="p-2 -mr-2 text-muted hover:text-foreground transition-colors">
            <span class="sr-only">{{ __('Close menu', 'apiary') }}</span>
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Mobile nav items --}}
    <div class="py-3 px-2">
        {!! wp_nav_menu([
            'walker' => new MenuPrimary(),
            'theme_location' => 'menu-primary',
            'container' => false,
            'items_wrap' => '%3$s',
            'fallback_cb' => false,
            'mobile' => true,
        ]) !!}
    </div>

    {{-- Mobile nav footer --}}
    <div class="mt-auto border-t border-outline px-4 py-4">
        <a href="{{ get_permalink( get_option('woocommerce_myaccount_page_id') ) }}" class="flex items-center gap-3 py-2 text-sm font-medium text-foreground no-underline hover:text-primary transition-colors">
            <svg class="w-5 h-5 text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
            </svg>
            @if (is_user_logged_in())
                {{ __('My Account','woocommerce') }}
            @else
                {{ __('Login / Register','woocommerce') }}
            @endif
        </a>
    </div>
</div>
@endif
