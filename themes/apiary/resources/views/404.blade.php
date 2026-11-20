{{--
 * 404 - Page not found redirect
 *
 * @package Theme\Apiary
 --}}
@extends('layouts.app')

@section('content')
    <div class="flex flex-col items-center justify-center py-24 text-center" data-pollora-template="404">
        <p class="text-sm font-semibold text-primary uppercase tracking-wide">404</p>
        <h1 class="mt-4 text-3xl font-bold tracking-tight text-foreground sm:text-5xl">{{ __('Page not found', 'apiary') }}</h1>
        <p class="mt-4 text-base text-muted max-w-md">{{ __('Sorry, we couldn\'t find the page you\'re looking for. It might have been moved or no longer exists.', 'apiary') }}</p>
        <div class="mt-10 flex items-center gap-x-4">
            <a href="{{ home_url('/') }}"
               class="inline-flex items-center whitespace-nowrap rounded-md bg-primary px-6 py-3 text-sm font-semibold text-white no-underline shadow-xs transition-colors hover:bg-primary-hover">
                {{ __('Go home', 'apiary') }}
                <svg class="ml-2 -mr-0.5 h-4 w-4 min-w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 1 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd" />
                </svg>
            </a>
            @if (class_exists('WooCommerce'))
                <a href="{{ wc_get_page_permalink('shop') }}"
                   class="inline-flex items-center whitespace-nowrap text-sm font-semibold text-foreground transition-colors hover:text-primary">
                    {{ __('Browse products', 'woocommerce') }}

                    <svg class="ml-1.5 size-4 align-middle"
                         xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 20 20"
                         fill="currentColor"
                         aria-hidden="true">
                        <path fill-rule="evenodd"
                              d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 1 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z"
                              clip-rule="evenodd" />
                    </svg>
                </a>
            @endif
        </div>
    </div>
@endsection
