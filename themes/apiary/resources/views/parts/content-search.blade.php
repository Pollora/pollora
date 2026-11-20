{{--
 * Search result content partial
 *
 * @package Theme\Apiary
 --}}
<article id="post-{{ get_the_ID() }}" {!! post_class() !!}>
    <header class="entry-header">
        <h2 class="entry-title">
            <a href="{{ esc_url(get_permalink()) }}" rel="bookmark">@title</a>
        </h2>
        @if('post' === get_post_type())
            <div class="entry-meta">
                {!! posted_on() !!}
                {!! posted_by() !!}
            </div>
        @endif
    </header>
    {!! post_thumbnail() !!}
    <div class="entry-summary">
        @excerpt
    </div>
    <footer class="entry-footer">
        @php(entry_footer())
    </footer>
</article><!-- #post-{{ get_the_ID() }} -->
