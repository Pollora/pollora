@extends('layouts.app')

@section('content')
    <div data-pollora-template="author" class="mx-auto max-w-5xl px-6 py-16 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-foreground sm:text-4xl">
            {{ get_the_author() }}
        </h1>

        @if(get_the_author_meta('description'))
            <div class="mt-4 text-muted">
                {{ get_the_author_meta('description') }}
            </div>
        @endif

        @hasposts
            <div class="mt-10 space-y-8">
                @posts
                    @include('parts.content')
                @endposts
            </div>
        @endhasposts
        @noposts
            <p class="mt-10 text-muted">No posts by this author.</p>
        @endnoposts
    </div>
@endsection
