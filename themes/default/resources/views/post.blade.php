@extends('layouts.app')

@section('content')
    @loop
        @include('parts.content')
    @endloop
@endsection
