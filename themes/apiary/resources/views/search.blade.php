@extends('layouts.app')

@section('content')
    <div data-pollora-template="search">
        <h1>Search results for: {{ get_search_query() }}</h1>
        @hasposts
            @posts
                <article>
                    <h2><a href="@permalink">@title</a></h2>
                </article>
            @endposts
        @endhasposts
        @noposts
            <p>No results found.</p>
        @endnoposts
    </div>
@endsection
