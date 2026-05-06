<!DOCTYPE html>
<html {!! get_language_attributes() ?: 'lang="' . app()->getLocale() . '"' !!} class="h-full antialiased">
<head>
    <meta charset="{{ get_bloginfo('charset') ?: 'utf-8' }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    @wphead
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="{{ $post->classes ?? '' }} flex min-h-full flex-col bg-surface">
    <header class="sticky top-0 z-50 border-b border-border bg-white/80 backdrop-blur-lg">
        <div class="mx-auto flex max-w-5xl items-center justify-between px-6 py-4 lg:px-8">
            <a href="{{ home_url('/') }}" class="flex items-center gap-3">
                <img src="{{ Asset::url('images/pollora-logo.svg') }}" alt="{{ get_bloginfo('name') }}" class="h-10 w-auto">
            </a>
            <nav class="hidden md:block">
                <ul class="flex items-center gap-1">
                    @foreach($menu as $item)
                        <li>
                            <a class="rounded-lg px-4 py-2 text-sm font-medium text-foreground/70 transition hover:bg-surface-alt hover:text-primary"
                               href="{{ $item->url }}">
                                {{ $item->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>
            <button type="button"
                    class="md:hidden rounded-lg p-2 text-foreground/70 hover:bg-surface-alt"
                    aria-label="Menu"
                    aria-expanded="false"
                    onclick="this.setAttribute('aria-expanded', this.getAttribute('aria-expanded') === 'false' ? 'true' : 'false'); document.getElementById('mobile-menu').classList.toggle('hidden');">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
        </div>
        <nav id="mobile-menu" class="hidden border-t border-border md:hidden">
            <ul class="mx-auto max-w-5xl space-y-1 px-6 py-3">
                @foreach($menu as $item)
                    <li>
                        <a class="block rounded-lg px-4 py-2 text-sm font-medium text-foreground/70 transition hover:bg-surface-alt hover:text-primary"
                           href="{{ $item->url }}">
                            {{ $item->title }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>
    </header>

    <main class="flex-1">
        @yield('content')
    </main>

    <footer class="border-t border-border bg-white">
        <div class="mx-auto max-w-5xl px-6 py-12 lg:px-8">
            <div class="flex flex-col items-center gap-6 sm:flex-row sm:justify-between">
                <a href="{{ home_url('/') }}" class="flex items-center gap-2">
                    <img src="{{ Asset::url('images/pollora-logo.svg') }}" alt="{{ get_bloginfo('name') }}" class="h-8 w-auto">
                </a>
                <p class="text-sm text-muted">
                    Powered by <a href="https://github.com/Pollora" class="font-medium text-foreground hover:text-primary transition">Pollora</a>
                    — Laravel meets WordPress.
                </p>
            </div>
        </div>
    </footer>
    @wpfooter
</body>
</html>