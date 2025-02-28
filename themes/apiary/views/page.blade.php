@extends('layouts.app')

@section('content')
    <div class="mt-9">
        <h1 class="text-4xl font-bold tracking-tight text-zinc-800 sm:text-5xl">@title</h1>
        <div class="mt-6 prose prose-stone">
            @content
        </div>
    </div>
@endsection
