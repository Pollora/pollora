{{--
 * Breadcrumb navigation
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package Theme\Apiary\WooCommerce
 * @version 2.3.0
 --}}
@if ( !empty($breadcrumb) && !is_front_page() )
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <nav aria-label="Breadcrumb" class="py-3">
        <ol role="list" class="flex items-center gap-1.5 list-none pl-0">
            @foreach ( $breadcrumb as $key => $crumb )
                @if ( ! empty( $crumb[1] ) && sizeof( $breadcrumb ) !== $key + 1 )
                    <li class="flex items-center gap-1.5">
                        <a href="{{ $crumb[1] }}" class="text-xs text-muted hover:text-foreground transition-colors">
                            {!! $crumb[0] !!}
                        </a>
                        <svg class="h-3 w-3 shrink-0 text-outline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                        </svg>
                    </li>
                @else
                    <li class="text-xs">
                        <span aria-current="page" class="text-subtle">
                            {!! $crumb[0] !!}
                        </span>
                    </li>
                @endif
            @endforeach
        </ol>
    </nav>
</div>
@endif
