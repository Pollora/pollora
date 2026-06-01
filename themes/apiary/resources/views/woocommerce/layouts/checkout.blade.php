{{--
 * Simplified checkout layout (no nav/footer)
 *
 * @package Theme\Apiary\WooCommerce
 --}}
<!doctype html>
<html {!! get_language_attributes() !!}>
<head>
    <meta charset="{{ get_bloginfo('charset') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    @wphead
</head>
<body @bodyclass('antialiased font-sans bg-surface-alt overflow-x-hidden') x-data="{ loginModalOpen: false, panelOpen: false }" x-on:panel-open.window="panelOpen = true" x-on:panel-close.window="panelOpen = false" x-bind:class="{ 'overflow-hidden': panelOpen }" @keydown.window.escape="loginModalOpen = false">

@php do_action( 'wp_body_open' ); @endphp

<a href="#main" class="absolute top-0 left-2 z-[100] -translate-y-full focus:translate-y-2 bg-primary text-white no-underline px-4 py-2 rounded-md text-sm font-medium transition-transform">
    {{ __('Skip to content', 'apiary') }}
</a>

<div id="page" class="site min-h-screen flex flex-col">
    @include('woocommerce.parts.header.header-checkout')

    <div id="content" class="site-content flex-1">
        <div id="primary" class="content-area">
            <main id="main" class="site-main py-8 sm:py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @yield('content')
            </main>
        </div>
    </div>

    @include('woocommerce.parts.footer.footer-checkout')
</div>

@wpfooter
</body>
</html>
