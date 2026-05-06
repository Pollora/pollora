<!doctype html>
<html {!! get_language_attributes() !!}>
<head>
    <title>{{ wp_title() }}</title>
    <meta charset="{{ get_bloginfo('charset') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    @wphead
</head>
<body @bodyclass('antialiased font-sans bg-gray-200 overflow-x-hidden')>

@php do_action( 'wp_body_open' ); @endphp

<div id="page" class="site bg-white">

    @php
        do_action( 'before_header' );
    @endphp
    @include('parts.header')
    @php
        do_action( 'after_header' );
    @endphp

    <div id="content" class="site-content">
        <div id="primary" class="content-area">
            <main id="main" class="site-main mt-8 max-w-2xl mx-auto pb-16 px-4 sm:pb-24 sm:px-6 lg:max-w-7xl lg:px-8">
                @yield('content')
            </main>
        </div>
        <!-- Sidebar -->
        @if(is_active_sidebar('sidebar-1'))
            <aside id="secondary" class="widget-area">
                @php
                    dynamic_sidebar('sidebar-1')
                @endphp
            </aside>
        @endif
        <!-- End sidebar -->
    </div><!-- #content -->

    @php
        do_action( 'before_footer' );
    @endphp
    @include('parts.footer')

    @php
        do_action( 'after_footer' );
    @endphp

</div><!-- #page -->

@wpfooter
</body>
</html>
