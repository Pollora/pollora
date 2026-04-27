<?php

declare(strict_types=1);

namespace App\Cms\Rest;

use Pollora\Attributes\WpRestRoute;
use Pollora\Attributes\WpRestRoute\Method;
use WP_REST_Request;
use WP_REST_Response;

/**
 * Test case: REST controller for framework status.
 *
 * Validates that WpRestRoute discovery works with:
 * - WP_REST_Request injection via type-hint
 * - #[Method] attribute for HTTP method specification
 *
 * @covers Pollora\Attributes\WpRestRoute
 * @covers Pollora\Attributes\WpRestRoute\Method
 *
 * Endpoints:
 *   GET  /wp-json/starter/v1/status
 */
#[WpRestRoute(namespace: 'starter/v1', route: '/status')]
class StatusController
{
    /**
     * GET /wp-json/starter/v1/status
     * Returns framework health status.
     */
    #[Method('GET')]
    public function index(WP_REST_Request $request): WP_REST_Response
    {
        return new WP_REST_Response([
            'status' => 'ok',
            'php' => PHP_VERSION,
            'wordpress' => get_bloginfo('version'),
            'theme' => get_stylesheet(),
            'post_types' => array_keys(get_post_types(['_builtin' => false])),
            'taxonomies' => array_keys(get_taxonomies(['_builtin' => false])),
        ]);
    }
}
