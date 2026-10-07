@extends('layouts.app')

@section('content')
    <article class="mx-auto max-w-3xl px-6 py-16 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-foreground sm:text-4xl">@title</h1>
        <div class="entry-content is-layout-flow mt-8">
            @content
        </div>
    </article>
@endsection