@extends('layouts.app')

@section('content')
    <div class="mt-9">
        <div class="relative">
            @query([
            'post_type' => 'page',
            'limit' => 1,
            ])

            @posts
            <div class="max-w-2xl">
                <h1 class="text-4xl font-bold tracking-tight text-zinc-800 sm:text-5xl">@title</h1>
                <p class="mt-6 text-base text-zinc-600">@excerpt</p>
            </div>
            @endposts
        </div>
    </div>

    <div class="mt-24 md:mt-28">
        <div class="relative">
            <div class="mx-auto grid max-w-xl grid-cols-1 gap-y-20 lg:max-w-none lg:grid-cols-2">
                @hasposts
                <div class="flex flex-col gap-16">
                    @loop
                    <article class="group relative flex flex-col items-start">
                        <h2 class="text-base font-semibold tracking-tight text-zinc-800">
                            <div class="absolute -inset-x-4 -inset-y-6 z-0 scale-95 bg-zinc-50 opacity-0 transition group-hover:scale-100 group-hover:opacity-100 sm:-inset-x-6 sm:rounded-2xl"></div>
                            <a href="@permalink">
                                <span class="absolute -inset-x-4 -inset-y-6 z-20 sm:-inset-x-6 sm:rounded-2xl"></span>
                                <span class="relative z-10">@title</span>
                            </a>
                        </h2>
                        <time class="relative z-10 order-first mb-3 flex items-center text-sm text-zinc-400 pl-3.5" datetime="@published('c')">
                                            <span class="absolute inset-y-0 left-0 flex items-center" aria-hidden="true">
                                                <span class="h-4 w-0.5 rounded-full bg-zinc-200"></span>
                                            </span>
                            @published
                        </time>
                        <p class="relative z-10 mt-2 text-sm text-zinc-600">
                            @excerpt
                        </p>
                        <div aria-hidden="true" class="relative z-10 mt-4 flex items-center text-sm font-medium text-teal-500">Read article <svg viewBox="0 0 16 16" fill="none" aria-hidden="true" class="ml-1 h-4 w-4 stroke-current">
                                <path d="M6.75 5.75 9.25 8l-2.5 2.25" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>
                    </article>
                    @endloop
                </div>
                @endhasposts
                <div class="space-y-10 lg:pl-16 xl:pl-24">
                    <form class="rounded-2xl border border-zinc-100 p-6" action="/">
                        <h2 class="flex text-sm font-semibold text-zinc-900">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" class="h-6 w-6 flex-none">
                                <path d="M2.75 7.75a3 3 0 0 1 3-3h12.5a3 3 0 0 1 3 3v8.5a3 3 0 0 1-3 3H5.75a3 3 0 0 1-3-3v-8.5Z" class="fill-zinc-100 stroke-zinc-400"></path>
                                <path d="m4 6 6.024 5.479a2.915 2.915 0 0 0 3.952 0L20 6" class="stroke-zinc-400"></path>
                            </svg>
                            <span class="ml-3">Stay up to date</span>
                        </h2>
                        <p class="mt-2 text-sm text-zinc-600">Get notified when I publish something new, and unsubscribe at any time.</p>
                        <div class="mt-6 flex">
                            <input type="email" placeholder="Email address" aria-label="Email address" required="" class="min-w-0 flex-auto appearance-none rounded-md border border-zinc-900/10 bg-white px-3 py-[calc(theme(spacing.2)-1px)] shadow-md shadow-zinc-800/5 placeholder:text-zinc-400 focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 sm:text-sm">
                            <button class="inline-flex items-center gap-2 justify-center rounded-md py-2 px-3 text-sm outline-offset-2 transition active:transition-none bg-zinc-800 font-semibold text-zinc-100 hover:bg-zinc-700 active:bg-zinc-800 active:text-zinc-100/70 ml-4 flex-none" type="submit">Join</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
