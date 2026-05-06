<article id="post-{{ get_the_ID() }}" {!! post_class('mx-auto max-w-3xl px-6 py-16 lg:px-8') !!}>
    <header>
        @if(is_singular())
            <h1 class="text-3xl font-bold tracking-tight text-foreground sm:text-4xl">
                @title
            </h1>
        @else
            <h2 class="text-3xl font-bold tracking-tight text-foreground sm:text-4xl">
                <a href="@permalink" class="hover:text-primary transition" rel="bookmark">
                    @title
                </a>
            </h2>
        @endif

        @if('post' === get_post_type())
            <div class="mt-4 flex items-center gap-4 text-sm text-muted">
                <time datetime="@published('c')">@published</time>
            </div>
        @endif
    </header>

    <div class="entry-content mt-8 text-base leading-relaxed text-foreground/80">
        @content
    </div>
</article>