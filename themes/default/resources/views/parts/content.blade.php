<article id="post-{{ Loop::id() }}" {!! post_class() !!}>
    <header class="entry-header">
        @if(is_singular())
            <h1 class="entry-title text-4xl font-bold tracking-tight text-zinc-800 sm:text-5xl">{!! Loop::title() !!}</h1>
        @else
            <h2 class="entry-title text-4xl font-bold tracking-tight text-zinc-800 sm:text-5xl">
                <a href="{{ esc_url(get_permalink()) }}" rel="bookmark">{!! Loop::title() !!}</a>
            </h2>
        @endif

        @if('post' === get_post_type())
            <div class="entry-meta relative z-10 order-first mb-3 flex items-center text-sm text-zinc-400 mt-4 pl-3.5 space-x-4">
                <span class="absolute inset-y-0 left-0 flex items-center" aria-hidden="true">
                    <span class="h-4 w-0.5 rounded-full bg-zinc-200"></span>
                </span>
                {!! posted_on() !!}
            </div>
        @endif
    </header>
    <div class="entry-content mt-6 prose prose-stone">
        {!! Loop::content(sprintf(
            wp_kses(
                __('Continue reading<span class="screen-reader-text"> "%s"</span>', 'pollora'),
                [
                    'span' => [
                        'class' => []
                    ]
                ]
            ),
            Loop::title()
        )) !!}
        {!!
            wp_link_pages([
                'before' => '<div class="page-links">'.esc_html__('Pages:', 'pollora'),
                'after' => '</div>',
                'echo' => false
            ]);
        !!}
    </div>
</article><!-- #post-{{ Loop::id() }} -->
