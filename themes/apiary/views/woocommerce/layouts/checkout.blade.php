<!doctype html>
<html {!! get_language_attributes() !!}>
<head>
    <meta charset="{{ get_bloginfo('charset') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    @head
</head>
<body {{ body_class('antialiased font-sans bg-gray-200 overflow-x-hidden') }} x-data="{ loginModalOpen: false }" @keydown.window.escape="loginModalOpen = false">
    <div id="page" class="site bg-white">
        @include('woocommerce.parts.header.header-checkout')
        <div id="content" class="site-content">
            <div id="primary" class="content-area">
                <main id="main" class="site-main mt-8 max-w-2xl mx-auto pt-4 pb-16 px-4 sm:pb-24 sm:px-6 lg:max-w-7xl lg:px-8">
                    @yield('content')
                </main>
            </div>
        </div><!-- #content -->
        @include('woocommerce.parts.footer.footer-checkout')
    </div><!-- #page -->
    @footer
</body>
</html>
