{{--
    The name the WordPress template hierarchy looks for.

    Without this file a missing page renders index.blade.php — the generic
    fallback — and the screen below is never shown on any skeleton that lets
    the hierarchy choose. The status code was right, so nothing reported it.

    errors/404.blade.php stays for skeletons up to v13.4, whose routes ask for
    it by name: Route::wp('404', fn () => response()->view('errors.404', [], 404)).
    Both render the same body.
--}}
@extends('layouts.app')

@section('content')
    @include('parts.not-found')
@endsection
