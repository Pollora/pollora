@extends('theme::layouts.main')

@section('body')
    @loop
        @include('theme::parts.content')
    @endloop
@endsection
