{{--
 * Single project post type template
 *
 * @package Theme\Apiary
 --}}
@extends('layouts.app')

@section('content')
    <div data-pollora-template="single-project">
        <h1>@title</h1>
        <div>@content</div>
    </div>
@endsection
