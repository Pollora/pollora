@extends('layouts.app')

@section('content')
    <article data-pollora-template="single-project" class="mx-auto max-w-3xl px-6 py-16 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-foreground sm:text-4xl">@title</h1>
        <div class="entry-content mt-8 text-base leading-relaxed text-foreground/80">
            @content
        </div>
    </article>
@endsection
