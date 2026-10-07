{{--
 * Single post template
 *
 * @package Theme\Apiary
 --}}
@extends('layouts.app')

@section('content')
<!-- data-pollora-template="single" -->
    @posts
    @include('parts.content')
    @endposts
@endsection
