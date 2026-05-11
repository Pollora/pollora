@extends('layouts.app')

@section('content')
    <div data-pollora-template="archive">
        <h1>{!! get_the_archive_title() !!}</h1>
        @hasposts
            @posts
                <article>
                    <h2><a href="@permalink">@title</a></h2>
                    <div>@excerpt</div>
                </article>
            @endposts
        @endhasposts
        @noposts
            <p>No posts found.</p>
        @endnoposts
    </div>
@endsection
