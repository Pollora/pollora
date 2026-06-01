{{--
 * 404 error page content
 *
 * @package Theme\Apiary
 --}}
@extends('layouts.app')

@section('content')
    <div class="flex flex-col items-center justify-center py-24 text-center">
        <p class="text-sm font-semibold text-primary uppercase tracking-wide">404</p>
        <h1 class="mt-4 text-3xl font-bold tracking-tight text-foreground sm:text-5xl">{{ __('Page not found', 'apiary') }}</h1>
        <p class="mt-4 text-base text-muted max-w-md">{{ __('Sorry, we couldn\'t find the page you\'re looking for. It might have been moved or no longer exists.', 'apiary') }}</p>
        <div class="mt-10 flex items-center gap-x-4">
            <a href="{{ home_url('/') }}" class="rounded-md bg-primary px-4 py-2.5 text-sm font-semibold text-white no-underline shadow-xs hover:bg-primary-hover transition-colors">
                {{ __('Go home', 'apiary') }}
            </a>
            @if (class_exists('WooCommerce'))
                <a href="{{ wc_get_page_permalink('shop') }}" class="text-sm font-semibold text-foreground hover:text-primary transition-colors">
                    {{ __('Browse products', 'woocommerce') }} <span aria-hidden="true">&rarr;</span>
                </a>
            @endif
        </div>
    </div>
@endsection
