{{--
    Kept for skeletons up to v13.4, whose routes/web.php renders a missing page
    with Route::wp('404', fn () => response()->view('errors.404', [], 404)).
    Those sites never consult the template hierarchy, so removing this file
    gives them a "View [errors.404] not found" the moment they pull this
    theme's latest tag.

    The hierarchy looks for 404.blade.php instead; both render the same body.
--}}
@extends('layouts.app')

@section('content')
    @include('parts.not-found')
@endsection
