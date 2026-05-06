@extends('layouts.app')

@section('content')
    @posts
        <article id="post-{{ get_the_ID() }}" {!! post_class() !!}>
            @content
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
        </article><!-- #post-{{ get_the_ID() }} -->
    @endposts
@endsection
