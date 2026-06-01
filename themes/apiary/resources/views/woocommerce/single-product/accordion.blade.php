{{--
 * Product tabs as accordion
 *
 * @package Theme\Apiary\WooCommerce
 --}}
@if (!empty($accordion_tabs))
<div class="mt-8 border-t border-outline" x-data="{ open: null }">
    @foreach ($accordion_tabs as $key => $tab)
    <div class="border-b border-outline">
        <button
            type="button"
            class="flex w-full items-center justify-between py-4 text-sm font-medium text-foreground hover:text-primary transition-colors"
            @click="open = open === '{{ $key }}' ? null : '{{ $key }}'"
            :aria-expanded="open === '{{ $key }}' ? 'true' : 'false'"
            aria-controls="accordion-{{ $key }}"
        >
            <span>{!! wp_kses_post(apply_filters('woocommerce_product_' . $key . '_tab_title', $tab['title'], $key)) !!}</span>
            <svg
                class="h-5 w-5 shrink-0 text-muted transition-transform duration-200"
                :class="{ 'rotate-180': open === '{{ $key }}' }"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"
            >
                <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
            </svg>
        </button>
        <div
            id="accordion-{{ $key }}"
            x-show="open === '{{ $key }}'"
            x-collapse
            class="pb-4 prose prose-sm text-muted max-w-none"
        >
            @if (isset($tab['callback']))
                {!! call_user_func($tab['callback'], $key, $tab) !!}
            @endif
        </div>
    </div>
    @endforeach
</div>
@endif
