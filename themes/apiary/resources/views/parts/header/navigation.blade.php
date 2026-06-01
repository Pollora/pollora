{{-- Row 2 — Desktop Navigation --}}
@php
use \Theme\Apiary\Walkers\MenuPrimary;
@endphp
@if (has_nav_menu('menu-primary'))
<nav aria-label="{{ __('Primary navigation', 'apiary') }}" class="hidden lg:block border-t border-b border-outline">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-1 -mb-px [&>:first-child]:-ml-3">
            {!! wp_nav_menu([
                'walker' => new MenuPrimary(),
                'theme_location' => 'menu-primary',
                'container' => false,
                'items_wrap' => '%3$s',
                'fallback_cb' => false,
            ]) !!}
        </div>
    </div>
</nav>
@endif
