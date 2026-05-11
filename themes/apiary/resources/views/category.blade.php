@extends('layouts.app')

@section('content')
    <div data-pollora-template="category">
        <h1>{!! single_cat_title('', false) !!}</h1>
        @hasposts
            @posts
                <article>
                    <h2><a href="@permalink">@title</a></h2>
                </article>
            @endposts
        @endhasposts
    </div>
@endsection
