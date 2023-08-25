@extends('layouts.app')

@section('body')
    @loop
        @include('parts.content')
    @endloop
@endsection
