@extends('layouts.app')

@section('content')
    <div data-pollora-template="search" class="mx-auto max-w-5xl px-6 py-16 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-foreground sm:text-4xl">
            Search results for: <span class="text-primary">{{ get_search_query() }}</span>
        </h1>

        @hasposts
            <div class="mt-10 space-y-8">
                @posts
                    @include('parts.content')
                @endposts
            </div>
        @endhasposts
        @noposts
            <p class="mt-10 text-muted">No results found for "{{ get_search_query() }}".</p>
        @endnoposts
    </div>
@endsection
