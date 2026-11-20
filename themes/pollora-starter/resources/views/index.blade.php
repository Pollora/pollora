{{--
    The last step of the WordPress template hierarchy.

    Every request WordPress cannot match to a more specific template falls back
    here: archives, categories, tags, authors, dates, search results, custom
    post types, and any single view the theme has no dedicated template for.
    Without this file those requests render an empty page.
--}}
@extends('layouts.app')

@section('content')
    @hasposts
        @if(! is_singular())
            <header class="mx-auto max-w-3xl px-6 pt-16 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-foreground sm:text-4xl">
                    @if(is_search())
                        Results for &ldquo;{{ get_search_query() }}&rdquo;
                    @else
                        {!! get_the_archive_title() !!}
                    @endif
                </h1>

                @if(! is_search() && get_the_archive_description())
                    <div class="mt-4 text-base leading-relaxed text-muted">
                        {!! get_the_archive_description() !!}
                    </div>
                @endif
            </header>
        @endif

        @posts
            @include('parts.content')
        @endposts

        @if(! is_singular() && get_next_posts_link())
            <nav class="mx-auto flex max-w-3xl justify-between gap-4 px-6 pb-16 lg:px-8">
                <div>{!! get_previous_posts_link('&larr; Newer') !!}</div>
                <div>{!! get_next_posts_link('Older &rarr;') !!}</div>
            </nav>
        @endif
    @endhasposts

    @noposts
        <div class="mx-auto max-w-3xl px-6 py-32 text-center lg:px-8">
            <h1 class="text-2xl font-bold tracking-tight text-foreground">Nothing found</h1>
            <p class="mt-4 text-muted">
                @if(is_search())
                    No results for &ldquo;{{ get_search_query() }}&rdquo;. Try another search.
                @else
                    There is nothing to show here yet.
                @endif
            </p>

            <div class="mt-8">
                @include('parts.search')
            </div>

            <a href="{{ home_url('/') }}" class="no-underline mt-8 inline-flex items-center gap-2 rounded-xl bg-foreground px-6 py-3 text-sm font-semibold text-white shadow-lg transition hover:shadow-xl hover:-translate-y-0.5">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
                Back to home
            </a>
        </div>
    @endnoposts
@endsection
