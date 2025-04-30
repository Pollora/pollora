<?php

namespace Theme\Cms\Disabled;

use Pollora\Support\Facades\Filter;

class RestUser
{
    public function __construct()
    {
        if (! config('disable.rest_user')) {
            return;
        }

        // Disable default users API endpoints for security.
        Filter::add('rest_endpoints', [$this, 'disableRestEndpoints']);
    }

    public function disableRestEndpoints(array $endpoints)
    {
        if (isset($endpoints['/wp/v2/users'])) {
            unset($endpoints['/wp/v2/users']);
        }

        if (isset($endpoints['/wp/v2/users/(?P<id>[\d]+)'])) {
            unset($endpoints['/wp/v2/users/(?P<id>[\d]+)']);
        }

        return $endpoints;
    }
}
