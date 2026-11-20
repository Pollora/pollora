{{--
 * My account dashboard navigation card
 *
 * @package Theme\Apiary\WooCommerce
 --}}
<a href="{{ esc_url( $url ) }}"
   class="group rounded-xl border border-outline bg-white p-5 transition-all hover:border-primary/30 hover:shadow-md">
    <div class="flex items-start gap-4">
        <div class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-surface-alt text-muted group-hover:bg-primary/10 group-hover:text-primary transition-colors">
            @if (!empty($icons[$icon]))
                {!! $icons[$icon] !!}
            @endif
        </div>
        <div class="min-w-0">
            <h3 class="text-sm font-semibold text-foreground">{{ $title }}</h3>
            <p class="mt-1 text-xs text-muted leading-relaxed">{{ $description }}</p>
        </div>
    </div>
</a>
