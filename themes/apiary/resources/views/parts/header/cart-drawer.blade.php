{{-- Cart slide-over drawer --}}
<div x-data="{ open: false }"
     x-on:panel-open.window="open = ($event.detail.panel === 'cart')"
     x-on:panel-close.window="open = false"
     @keydown.window.escape="if (open) { window.dispatchEvent(new CustomEvent('panel-close')); }"
     class="fixed inset-y-0 right-0 pl-4 sm:pl-10 max-w-full flex z-40">
    <div x-show="open"
         x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
         x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
         role="dialog" aria-modal="true" aria-label="{{ __('Shopping cart', 'apiary') }}" class="w-screen max-w-md">
        <div class="h-full flex flex-col bg-white shadow-xl overflow-hidden">
            <?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
        </div>
    </div>
</div>
