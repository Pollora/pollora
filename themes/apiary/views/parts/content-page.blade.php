<article id="post-{{ Loop::id() }}" {!! post_class() !!}>
    <header class="entry-header">
        <h1 class="entry-title text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">{!! Loop::title() !!}</h1>
    </header><!-- .entry-header -->

    {!! post_thumbnail() !!}

    <div class="entry-content">
        {!! Loop::content() !!}
        {!! wp_link_pages([
            'before' => '<div class="page-links">'.esc_html__('Pages:', 'apiary'),
            'after' => '</div>',
            'echo' => false
        ]) !!}
    </div><!-- .entry-co ntent -->

    @if(get_edit_post_link())
        <footer class="entry-footer">
            @php(edit_post_link(
                sprintf(
                    wp_kses(
                        /* translators: %s: Name of current post. Only visible to screen readers */
                        __('Edit <span class="screen-reader-text">%s</span>', 'apiary'),
                        [
                            'span' => [
                                'class' => []
                            ]
                        ]
                    ),
                    get_the_title()
                ),
                '<span class="edit-link">',
                '</span>'
            ))
        </footer>
    @endif
</article><!-- #post-{{ Loop::id() }} -->
