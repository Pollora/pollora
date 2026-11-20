@extends('layouts.app')

@section('content')
    <div data-pollora-template="taxonomy" class="mx-auto max-w-5xl px-6 py-16 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-foreground sm:text-4xl">
            {!! single_term_title('', false) !!}
        </h1>

        @if(term_description())
            <div class="mt-4 text-muted">
                {!! term_description() !!}
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
            <p class="mt-10 text-muted">No posts found.</p>
        @endnoposts
    </div>
@endsection
