{{--
 * Archive template
 *
 * @package Theme\Apiary
 --}}
@extends('layouts.app')

@section('content')
    <div data-pollora-template="archive">
        <header class="border-b border-outline pt-8 pb-8">
            <h1 class="text-4xl font-extrabold tracking-tight text-foreground">{!! get_the_archive_title() !!}</h1>
            @if (get_the_archive_description())
                <p class="mt-2 text-sm text-muted">{!! get_the_archive_description() !!}</p>
            @endif
        </header>
        @hasposts
            <div class="divide-y divide-outline">
                @posts
                    <article class="py-8">
                        <time class="text-sm text-subtle" datetime="@published('c')">@published</time>
                        <h2 class="mt-1 text-xl font-semibold text-foreground hover:text-primary transition-colors">
                            <a href="@permalink">@title</a>
                        </h2>
                        <p class="mt-2 text-sm text-muted line-clamp-3">@excerpt</p>
                    </article>
                @endposts
            </div>
        @endhasposts
        @noposts
            <p class="py-12 text-muted">{{ __('No posts found.', 'apiary') }}</p>
        @endnoposts
    </div>
@endsection
