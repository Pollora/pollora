<?php

namespace Theme\Cms\Disabled;

use Pollora\Support\Facades\Filter;

class Medias
{
    public function __construct()
    {
        if (! config('disable.media')) {
            return;
        }

        Filter::add('template_redirect', [$this, 'disableMediaPages']);
        Filter::add('redirect_canonical', [$this, 'disableMediaPages'], 0);
        Filter::add('attachment_link', [$this, 'updateAttachmentLink'], 10, 2);
    }

    public function disableMediaPages()
    {
        if (! is_attachment()) {
            return;
        }

        global $wp_query;
        $wp_query->set_404();
        status_header(404);
    }

    public function updateAttachmentLink(string $url, int $id): string
    {
        if ($attachment_url = wp_get_attachment_url($id)) {
            return $attachment_url;
        }

        return $url;
    }
}
