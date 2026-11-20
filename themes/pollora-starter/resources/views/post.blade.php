{{--
    Kept for skeletons up to v13.4, whose routes/web.php renders a single post
    with Route::wp('single', fn () => view('post')). Those sites never consult
    the template hierarchy, so removing this file gives them a "View [post] not
    found" 500 the moment they pull this theme's latest tag.

    From v13.32 on, the hierarchy decides and single.blade.php is what answers;
    this file is then unused. Same body as single.blade.php on purpose — the
    two are one template under two names, not a variant.
--}}
@extends('layouts.app')

@section('content')
    @posts
        @include('parts.content')
    @endposts
@endsection
