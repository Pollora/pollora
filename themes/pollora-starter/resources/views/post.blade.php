@extends('layouts.app')

@section('content')
    <div data-pollora-template="single">
    @posts
        @include('parts.content')
    @endposts
    </div>
@endsection