{{-- Backdrop overlay — listens for any panel open/close --}}
<div x-data="{ visible: false }"
     x-on:panel-open.window="visible = true"
     x-on:panel-close.window="visible = false"
     x-show="visible"
     x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
     class="fixed inset-0 bg-black/25 z-20"
     @click="window.dispatchEvent(new CustomEvent('panel-close'))"
     aria-hidden="true"
     style="display: none;">
</div>
