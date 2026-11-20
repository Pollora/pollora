{{--
 * Author archive template
 *
 * @package Theme\Apiary
 --}}
@extends('layouts.app')

@section('content')
    <div data-pollora-template="author">
        <h1>{{ get_the_author() }}</h1>
        @hasposts
            @posts
                <article>
                    <h2><a href="@permalink">@title</a></h2>
                </article>
            @endposts
        @endhasposts
    </div>
@endsection
