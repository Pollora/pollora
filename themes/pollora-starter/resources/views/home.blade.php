@extends('layouts.app')

@section('content')
    <section data-pollora-template="home" class="relative overflow-hidden bg-gradient-to-br from-[#2d1b4e] via-[#1d142a] to-[#2d1b4e]">
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.15) 1px, transparent 0); background-size: 40px 40px;"></div>
        <div class="absolute -top-24 -right-24 h-96 w-96 rounded-full bg-primary-vivid/10 blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 h-96 w-96 rounded-full bg-secondary/10 blur-3xl"></div>
        <div class="relative mx-auto max-w-5xl px-6 py-24 lg:px-8 lg:py-32">
            <div class="max-w-2xl">
                <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-1.5 text-sm text-white/70 backdrop-blur-sm">
                    <span class="inline-block h-2 w-2 rounded-full bg-gradient-to-r from-primary-vivid to-secondary"></span>
                    Laravel + WordPress, unified
                </div>
                <h1 class="text-4xl font-bold tracking-tight text-white sm:text-5xl">
                    Build <span class="bg-gradient-to-r from-primary-vivid via-primary to-secondary bg-clip-text text-transparent">modern sites</span>
                    with Pollora
                </h1>
                <p class="mt-6 text-lg leading-relaxed text-white/60">
                    The framework that bridges Laravel's elegance with WordPress's content management.
                    Ship faster, build smarter.
                </p>
                <div class="mt-10 flex flex-wrap gap-4">
                    <a href="https://github.com/Pollora" class="wp-element-button inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-primary to-secondary px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-primary/30 transition hover:shadow-xl hover:shadow-primary/40 hover:-translate-y-0.5">
                        Get started
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                    </a>
                    <a href="https://github.com/Pollora/framework" class="wp-element-button inline-flex items-center gap-2 rounded-xl border border-white/20 bg-white/5 px-6 py-3 text-sm font-semibold text-white backdrop-blur-sm transition hover:bg-white/10 hover:-translate-y-0.5">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0 1 12 6.844a9.59 9.59 0 0 1 2.504.337c1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.02 10.02 0 0 0 22 12.017C22 6.484 17.522 2 12 2Z" clip-rule="evenodd" /></svg>
                        View on GitHub
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-5xl px-6 py-20 lg:px-8">
        <div class="grid gap-16 lg:grid-cols-3">
            <div class="group rounded-2xl border border-border bg-white p-8 shadow-sm transition hover:border-primary/20 hover:shadow-md">
                <div class="mb-4 inline-flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-primary-vivid to-primary text-white">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" /></svg>
                </div>
                <h3 class="text-lg font-semibold text-foreground">PHP Attributes</h3>
                <p class="mt-2 text-sm leading-relaxed text-muted">Register post types, taxonomies, hooks and schedules with elegant PHP 8 attributes.</p>
            </div>
            <div class="group rounded-2xl border border-border bg-white p-8 shadow-sm transition hover:border-secondary/20 hover:shadow-md">
                <div class="mb-4 inline-flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-primary to-secondary text-white">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5" /></svg>
                </div>
                <h3 class="text-lg font-semibold text-foreground">Blade + WordPress</h3>
                <p class="mt-2 text-sm leading-relaxed text-muted">Use Laravel's Blade templates with WordPress-specific directives like @loop, @query and @wphead.</p>
            </div>
            <div class="group rounded-2xl border border-border bg-white p-8 shadow-sm transition hover:border-accent/20 hover:shadow-md">
                <div class="mb-4 inline-flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-secondary to-accent text-white">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                </div>
                <h3 class="text-lg font-semibold text-foreground">Hybrid Routing</h3>
                <p class="mt-2 text-sm leading-relaxed text-muted">Combine Laravel routes with WordPress template hierarchy using Route::wp() for seamless integration.</p>
            </div>
        </div>
    </section>

    @query(['post_type' => 'post', 'posts_per_page' => 4])
    @hasposts
    <section class="border-t border-border bg-white">
        <div class="mx-auto max-w-5xl px-6 py-20 lg:px-8">
            <h2 class="text-2xl font-bold tracking-tight text-foreground">Latest posts</h2>
            <div class="mt-10 grid gap-8 sm:grid-cols-2">
                @posts
                <article class="group relative rounded-2xl border border-border bg-surface p-6 transition hover:border-primary/20 hover:shadow-md">
                    <time class="text-xs font-medium uppercase tracking-wider text-muted" datetime="@published('c')">
                        @published
                    </time>
                    <h3 class="mt-3 text-lg font-semibold text-foreground group-hover:text-primary transition">
                        <a href="@permalink">
                            <span class="absolute inset-0"></span>
                            @title
                        </a>
                    </h3>
                    <p class="mt-2 text-sm leading-relaxed text-muted line-clamp-3">
                        @excerpt
                    </p>
                    <div class="mt-4 flex items-center text-sm font-medium text-primary">
                        Read more
                        <svg class="ml-1 h-4 w-4 transition group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                    </div>
                </article>
                @endposts
            </div>
        </div>
    </section>
    @endhasposts
@endsection
