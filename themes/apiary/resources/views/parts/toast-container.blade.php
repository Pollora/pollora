{{-- Alpine.js toast notification container --}}
<div x-data x-cloak
     aria-live="polite"
     class="fixed top-4 right-4 z-50 flex flex-col items-end gap-3 pointer-events-none">
    <template x-for="toast in $store.toasts.list" :key="toast.id">
        <div x-show="toast.visible"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-full"
             class="pointer-events-auto flex items-center gap-3 max-w-sm px-4 py-3 rounded-xl bg-white shadow-lg ring-1 ring-outline/80 text-sm text-foreground cursor-pointer"
             @click="$store.toasts.dismiss(toast.id)">

            {{-- Success icon --}}
            <template x-if="toast.type === 'success'">
                <svg class="w-5 h-5 shrink-0 text-success" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </template>

            {{-- Info icon --}}
            <template x-if="toast.type === 'info'">
                <svg class="w-5 h-5 shrink-0 text-info" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </template>

            {{-- Error icon --}}
            <template x-if="toast.type === 'error'">
                <svg class="w-5 h-5 shrink-0 text-error" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </template>

            <div class="flex-1" x-text="toast.message"></div>

            <button @click.stop="$store.toasts.dismiss(toast.id)"
                    class="shrink-0 p-1 text-subtle hover:text-foreground transition-colors"
                    aria-label="{{ __('Dismiss', 'apiary') }}">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </template>
</div>
