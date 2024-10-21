<!DOCTYPE html>
<html {!! get_language_attributes() ?: 'lang="' . app()->getLocale() . '"' !!}
      class="h-full antialiased light"
      style="color-scheme: light; --header-position: sticky; --content-offset: 116px; --header-height: 180px; --header-mb: -116px; --avatar-image-transform: translate3d(0rem, 0, 0) scale(1); --avatar-border-transform: translate3d(-0.2222222222222222rem, 0, 0) scale(1.7777777777777777); --avatar-border-opacity: 0; --header-top: 0px; --avatar-top: 0px;"
>
<head>
    <meta charset="{{ get_bloginfo('charset') ?: 'utf-8' }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    @wphead

    {{--
        Vite::useBuildDirectory('public/build/default') // Customize the build directory...
            ->withEntryPoints(['themes/default/assets/app.js'])
    --}}
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Styles -->
    <link href="//fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
</head>

<body class="{{ $post->classes ?? '' }} flex h-full bg-zinc-50">
    <div class="flex w-full">
        <div class="fixed inset-0 flex justify-center sm:px-8">
            <div class="flex w-full max-w-7xl lg:px-8">
                <div class="w-full bg-white ring-1 ring-zinc-100"></div>
            </div>
        </div>
        <div class="relative flex w-full flex-col">
            <header class="pointer-events-none relative z-50 flex flex-none flex-col">
                <div class="order-last mt-[calc(theme(spacing.16)-theme(spacing.3))]"></div>
                <div class="top-0 z-10 h-16 pt-6" style="position:var(--header-position)">
                    <div class="sm:px-8 top-[var(--header-top,theme(spacing.6))] w-full"
                         style="position:var(--header-inner-position)">
                        <div class="mx-auto w-full max-w-7xl lg:px-8">
                            <div class="relative px-4 sm:px-8 lg:px-12">
                                <div class="mx-auto max-w-2xl lg:max-w-5xl">
                                    <div class="relative flex gap-4">
                                        <div class="flex flex-1 justify-end md:justify-center">
                                            <div class="pointer-events-auto md:hidden" data-headlessui-state="">
                                                <button
                                                    class="group flex items-center rounded-full bg-white/90 px-4 py-2 text-sm font-medium text-zinc-800 shadow-lg shadow-zinc-800/5 ring-1 ring-zinc-900/5 backdrop-blur"
                                                    type="button" aria-expanded="false" data-headlessui-state=""
                                                    id="headlessui-popover-button-:R2miqla:">Menu
                                                    <svg viewBox="0 0 8 6" aria-hidden="true"
                                                         class="ml-3 h-auto w-2 stroke-zinc-500 group-hover:stroke-zinc-700">
                                                        <path d="M1.75 1.75 4 4.25l2.25-2.5" fill="none" stroke-width="1.5"
                                                              stroke-linecap="round" stroke-linejoin="round"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                            <div
                                                style="position:fixed;top:1px;left:1px;width:1px;height:0;padding:0;margin:-1px;overflow:hidden;clip:rect(0, 0, 0, 0);white-space:nowrap;border-width:0;display:none"></div>
                                            <nav class="pointer-events-auto hidden md:block">
                                                <ul class="flex rounded-full bg-white/90 px-3 text-sm font-medium text-zinc-800 shadow-lg shadow-zinc-800/5 ring-1 ring-zinc-900/5 backdrop-blur">
                                                    @foreach($menu as $item)
                                                        <li>
                                                            <a class="relative block px-3 py-2 transition hover:text-teal-500"
                                                               href="{{ $item->url }}">
                                                                {{ $item->title }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-auto mx-auto max-w-2xl lg:max-w-5xl">
                <div class="px-4 sm:px-12 lg:px-20">
                    @yield('content')

                    <div class="max-w-[200px] my-20 mx-auto">
                        <img src="{{ Vite::image('pollora.svg') }}">
                    </div>
                </div>
            </main>
            @wpfooter
        </div>
    </div>
</body>
</html>
