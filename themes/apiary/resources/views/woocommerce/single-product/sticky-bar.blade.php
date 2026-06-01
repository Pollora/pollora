{{--
 * Sticky add-to-cart bar
 *
 * Shows a fixed bottom bar on mobile (always) and desktop (when add-to-cart button scrolls out).
 * For variable/grouped products, an expandable panel reveals the original form options.
 --}}
@php
    global $product;

    if ( ! $product ) {
        return;
    }

    $stickyConfig = config('theme.woocommerce.single-product.sticky_add_to_cart', []);
    if ( empty($stickyConfig['enabled']) ) {
        return;
    }

    $mobileEnabled = $stickyConfig['mobile'] ?? true;
    $desktopEnabled = $stickyConfig['desktop'] ?? true;

    if ( ! $mobileEnabled && ! $desktopEnabled ) {
        return;
    }

    $type = $product->get_type();

    // Grouped products are not directly purchasable but should still show the sticky bar
    if ( $type !== 'grouped' && $type !== 'external' && ( ! $product->is_purchasable() || ! $product->is_in_stock() ) ) {
        return;
    }

    $isComplex = in_array($type, ['variable', 'grouped']);
    $isExternal = $type === 'external';
    $thumbnail = $product->get_image('woocommerce_gallery_thumbnail', ['class' => 'w-10 h-10 rounded-lg object-cover']);
@endphp

<div id="sticky-add-to-cart"
     x-data="{ expanded: false, visible: false, panelOpen: false }"
     x-on:sticky-bar-show.window="if (!panelOpen) visible = true"
     x-on:sticky-bar-hide.window="visible = false; expanded = false"
     x-on:panel-open.window="panelOpen = true; visible = false; expanded = false"
     x-on:panel-close.window="panelOpen = false; $nextTick(() => { const btn = document.querySelector('.single_add_to_cart_button'); if (btn && btn.getBoundingClientRect().bottom < 0) visible = true; })"
     x-show="visible"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="translate-y-full"
     x-transition:enter-end="translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="translate-y-0"
     x-transition:leave-end="translate-y-full"
     class="fixed bottom-0 inset-x-0 z-40 bg-white border-t border-outline shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]"
     style="display: none;">

    {{-- Expandable panel for complex products --}}
    @if ($isComplex)
        <div x-show="expanded"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 max-h-0"
             x-transition:enter-end="opacity-100 max-h-[50vh]"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 max-h-[50vh]"
             x-transition:leave-end="opacity-0 max-h-0"
             class="border-b border-outline bg-surface overflow-hidden"
             style="display: none;">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div id="sticky-bar-form-slot" class="max-h-[50vh] overflow-y-auto">
                    {{-- Form content will be cloned here by JS --}}
                </div>
            </div>
        </div>
    @endif

    {{-- Compact bar --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3 py-3">
            {{-- Product thumbnail + info (desktop) --}}
            <div class="hidden sm:flex items-center gap-3 flex-1 min-w-0">
                <div class="shrink-0">{!! $thumbnail !!}</div>
                <div class="min-w-0">
                    <p class="text-sm font-medium text-foreground truncate">{{ $product->get_name() }}</p>
                    <p class="text-sm font-semibold text-foreground [&_del]:text-xs [&_del]:text-subtle [&_del]:mr-1 [&_ins]:no-underline">{!! $product->get_price_html() !!}</p>
                </div>
            </div>

            {{-- Mobile: just price --}}
            <div class="sm:hidden flex-1 min-w-0">
                <p class="text-sm font-semibold text-foreground [&_del]:text-xs [&_del]:text-subtle [&_del]:mr-1 [&_ins]:no-underline">{!! $product->get_price_html() !!}</p>
            </div>

            {{-- Action area --}}
            @if ($isExternal)
                <a href="{{ esc_url( $product->get_product_url() ) }}"
                   target="_blank"
                   rel="nofollow noopener"
                   class="shrink-0 bg-primary text-white text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-primary-hover transition-colors no-underline">
                    {{ $product->single_add_to_cart_text() }}
                </a>
            @elseif ($isComplex)
                <button @click="expanded = !expanded"
                        :class="expanded && 'ring-2 ring-ring'"
                        class="sticky-bar-toggle shrink-0 bg-primary text-white text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-primary-hover transition-all flex items-center gap-2">
                    <span x-text="expanded ? '{{ __('Close', 'apiary') }}' : '{{ __('Select options', 'woocommerce') }}'">{{ __('Select options', 'woocommerce') }}</span>
                    <svg :class="expanded && 'rotate-180'" class="w-4 h-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
                    </svg>
                </button>
            @else
                {{-- Simple product: direct add-to-cart --}}
                <button class="sticky-bar-add shrink-0 bg-primary text-white text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-primary-hover transition-colors"
                        data-product-id="{{ $product->get_id() }}">
                    {{ $product->single_add_to_cart_text() }}
                </button>
            @endif
        </div>
    </div>
</div>
