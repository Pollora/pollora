<article id="post-{{ get_the_ID() }}" {!! post_class('max-w-3xl mx-auto px-6 py-12') !!}>
    <header class="entry-header mb-8">
        @if(is_singular())
            <h1 class="entry-title text-3xl font-bold tracking-tight text-gray-900">@title</h1>
        @else
            <h2 class="entry-title text-2xl font-bold tracking-tight text-gray-900">
                <a href="{{ esc_url(get_permalink()) }}" rel="bookmark">@title</a>
            </h2>
        @endif

        @if('post' === get_post_type())
            <div class="entry-meta mt-2 text-sm text-gray-500">
                {!! posted_on() !!}
                {!! posted_by() !!}
            </div>
        @endif
    </header>
    {!! post_thumbnail() !!}
    <div class="entry-content prose prose-gray max-w-none">
        {!! get_the_content(sprintf(
            wp_kses(
                __('Continue reading<span class="screen-reader-text"> "%s"</span>', 'apiary'),
                [
                    'span' => [
                        'class' => []
                    ]
                ]
            ),
            get_the_title()
        )) !!}
        {!!
            wp_link_pages([
                'before' => '<div class="page-links">'.esc_html__('Pages:', 'apiary'),
                'after' => '</div>',
                'echo' => false
            ]);
        !!}
    </div>
    <footer class="entry-footer">
        @php(entry_footer())
    </footer>
</article><!-- #post-{{ get_the_ID() }} -->
