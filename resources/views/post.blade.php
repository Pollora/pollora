@extends('layouts.main')

@section('body')
    @loop
        @include('parts.content')
    @endloop
@endsection
