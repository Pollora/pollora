{{-- Add-to-Cart Confirmation Modal --}}
@if (config('theme.woocommerce.single-product.add_to_cart_confirmation.enabled', false))
<div x-data="atcModal"
     x-show="open"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     role="dialog"
     aria-modal="true"
     aria-label="{{ __('Added to cart', 'apiary') }}"
     @keydown.window.escape="if (open) close()">

    {{-- Backdrop --}}
    <div x-show="open"
         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/40"
         @click="close()"></div>

    {{-- Modal panel --}}
    <div class="flex min-h-full items-center justify-center p-4">
        <div x-show="open"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl overflow-hidden"
             @click.outside="close()">

            {{-- Close button --}}
            <button @click="close()"
                    class="absolute top-4 right-4 p-1.5 text-subtle hover:text-foreground transition-colors z-10"
                    aria-label="{{ __('Dismiss', 'apiary') }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="p-6">
                {{-- Success header --}}
                <div class="flex items-center gap-2 mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-5 text-success shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                    <h3 class="text-base font-semibold text-foreground" x-text="(apiaryCart?.i18n?.addedToCartTitle || '{{ __('Added to cart', 'apiary') }}')"></h3>
                </div>

                {{-- Product card --}}
                <div class="flex gap-4" x-show="product">
                    <div class="shrink-0 size-20 sm:size-24 rounded-lg overflow-hidden bg-surface-alt">
                        <img :src="product?.image" :alt="product?.name" class="size-full object-cover" />
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-semibold text-foreground leading-snug" x-text="product?.name"></h4>
                        <p class="mt-0.5 text-xs text-muted" x-show="product?.variation" x-text="product?.variation"></p>
                        <div class="mt-1 text-xs text-muted line-clamp-2" x-show="product?.description" x-html="product?.description"></div>
                        <div class="mt-2 text-sm font-semibold text-foreground" x-show="product?.price" x-html="product?.price"></div>
                    </div>
                </div>

                {{-- CTAs --}}
                <div class="mt-6 flex flex-col sm:flex-row gap-2.5">
                    <a :href="apiaryCart?.checkoutUrl || '/checkout/'"
                       class="flex-1 inline-flex items-center justify-center rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-primary-hover transition-colors order-first sm:order-last">
                        <span x-text="apiaryCart?.i18n?.checkout || '{{ __('Checkout', 'apiary') }}'"></span>
                    </a>
                    <a :href="apiaryCart?.cartUrl || '/cart/'"
                       class="flex-1 inline-flex items-center justify-center rounded-lg border border-outline bg-white px-4 py-2.5 text-sm font-medium text-foreground shadow-xs hover:bg-surface-alt transition-colors">
                        <span x-text="apiaryCart?.i18n?.viewCart || '{{ __('View cart', 'apiary') }}'"></span>
                    </a>
                    <button @click="close()"
                            class="flex-1 inline-flex items-center justify-center rounded-lg border border-outline bg-white px-4 py-2.5 text-sm font-medium text-muted shadow-xs hover:bg-surface-alt hover:text-foreground transition-colors">
                        <span x-text="apiaryCart?.i18n?.continueShopping || '{{ __('Continue shopping', 'apiary') }}'"></span>
                    </button>
                </div>
            </div>

            {{-- Recommendations section --}}
            <template x-if="recommendations.length > 0 || loadingRecs">
                <div class="border-t border-outline bg-surface px-6 py-5">
                    <h4 class="text-xs font-semibold text-muted uppercase tracking-wider mb-3" x-text="recsTitle"></h4>

                    {{-- Loading skeleton --}}
                    <template x-if="loadingRecs && recommendations.length === 0">
                        <div class="grid grid-cols-4 gap-3">
                            <template x-for="i in 4" :key="i">
                                <div class="animate-pulse">
                                    <div class="aspect-square rounded-lg bg-outline/50"></div>
                                    <div class="mt-2 h-3 rounded bg-outline/50 w-3/4"></div>
                                    <div class="mt-1 h-3 rounded bg-outline/50 w-1/2"></div>
                                </div>
                            </template>
                        </div>
                    </template>

                    {{-- Product grid --}}
                    <template x-if="recommendations.length > 0">
                        <div class="grid grid-cols-4 gap-3">
                            <template x-for="rec in recommendations" :key="rec.id">
                                <a :href="rec.url" class="group block" @click="close()">
                                    <div class="aspect-square rounded-lg overflow-hidden bg-surface-alt">
                                        <img :src="rec.image" :alt="rec.name" class="size-full object-cover group-hover:scale-105 transition-transform duration-200" loading="lazy" />
                                    </div>
                                    <p class="mt-1.5 text-xs font-medium text-foreground leading-tight line-clamp-2 group-hover:text-primary transition-colors" x-text="rec.name"></p>
                                    <p class="mt-0.5 text-xs text-muted" x-html="rec.price"></p>
                                </a>
                            </template>
                        </div>
                    </template>
                </div>
            </template>
        </div>
    </div>

</div>
@endif
