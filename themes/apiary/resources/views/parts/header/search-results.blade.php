{{-- Search suggestions dropdown --}}
<div x-show="showResults"
     x-transition:enter="transition ease-out duration-150"
     x-transition:enter-start="opacity-0 translate-y-1"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-100"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 translate-y-1"
     @click.outside="close()"
     class="absolute z-50 left-0 right-0 mt-2 bg-white border border-outline rounded-xl shadow-lg overflow-hidden"
     style="display: none;">

    {{-- Loading indicator --}}
    <div x-show="loading && results.length === 0" class="px-4 py-6 text-center text-sm text-muted">
        <svg class="animate-spin size-5 mx-auto text-subtle" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    </div>

    {{-- No results --}}
    <div x-show="!loading && results.length === 0" class="px-4 py-6 text-center text-sm text-muted" style="display: none;">
        {{ __('No products found.', 'woocommerce') }}
    </div>

    {{-- Results list --}}
    <ul x-show="results.length > 0" class="divide-y divide-outline max-h-80 overflow-y-auto" role="listbox" style="display: none;">
        <template x-for="(item, index) in results" :key="item.id">
            <li role="option"
                :aria-selected="activeIndex === index"
                :class="activeIndex === index ? 'bg-surface' : ''"
                class="hover:bg-surface transition-colors cursor-pointer"
                @click="navigate(item.url)"
                @mouseenter="activeIndex = index">
                <div class="flex items-center gap-3 px-4 py-2.5">
                    <img :src="item.image"
                         :alt="item.title"
                         class="size-10 rounded-lg object-cover bg-surface shrink-0"
                         loading="lazy">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-foreground truncate" x-text="item.title"></p>
                        <p class="text-xs text-muted" x-html="item.price"></p>
                    </div>
                </div>
            </li>
        </template>
    </ul>

    {{-- View all link --}}
    <div x-show="results.length > 0" class="border-t border-outline" style="display: none;">
        <button type="submit" class="w-full px-4 py-2.5 text-center text-sm font-medium text-primary hover:bg-surface transition-colors">
            {{ __('View all results', 'woocommerce') }}
        </button>
    </div>
</div>
