{{--
 * Coming soon / maintenance page
 *
 * @package Theme\Apiary\WooCommerce
 --}}
<!doctype html>
<html {!! get_language_attributes() !!}>
<head>
    <meta charset="{{ get_bloginfo('charset') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="woo-coming-soon-page" content="yes">
    @wphead
</head>
<body @bodyclass('antialiased font-sans bg-white')>
@php do_action('wp_body_open'); @endphp

<div class="min-h-screen flex flex-col items-center justify-center px-6 py-12">

    {{-- Logo --}}
    @if(has_custom_logo())
        <div class="mb-8">
            {!! get_custom_logo() !!}
        </div>
    @else
        <h1 class="text-3xl font-bold tracking-tight text-foreground mb-8">
            {{ get_bloginfo('name') }}
        </h1>
    @endif

    {{-- Tagline --}}
    @if(get_bloginfo('description'))
        <p class="text-sm text-muted mb-12">{{ get_bloginfo('description') }}</p>
    @endif

    {{-- Main message --}}
    <div class="max-w-lg text-center">
        <h2 class="text-4xl font-extrabold tracking-tight text-foreground sm:text-5xl">
            {{ __('Great things are on the horizon', 'apiary') }}
        </h2>
        <p class="mt-6 text-lg text-muted leading-relaxed">
            {{ __('Something big is brewing! Our store is in the works and will be launching soon!', 'apiary') }}
        </p>
    </div>

    {{-- CTA --}}
    <div class="mt-12 flex items-center gap-x-4">
        <a href="{{ home_url('/') }}"
           class="inline-flex items-center whitespace-nowrap rounded-md bg-primary px-6 py-3 text-sm font-semibold text-white no-underline shadow-xs transition-colors hover:bg-primary-hover">
            {{ __('Go home', 'apiary') }}
            <svg class="ml-2 -mr-0.5 h-4 w-4 min-w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 1 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd" />
            </svg>
        </a>
    </div>

    {{-- Footer --}}
    <p class="mt-16 text-xs text-subtle">
        &copy; {{ date('Y') }} {{ get_bloginfo('name') }}
    </p>
</div>

@wpfooter
</body>
</html>
