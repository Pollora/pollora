<?php

declare(strict_types=1);

namespace App\Cms\Rest;

use Pollora\Attributes\WpRestRoute;
use WP_REST_Request;
use WP_REST_Response;

/**
 * Example REST API controller — discovered automatically by Pollora.
 *
 * Uses the #[WpRestRoute] attribute to register REST endpoints.
 * This class appears in the Pollora dashboard under "REST API Routes".
 */
#[WpRestRoute(namespace: 'starter/v1', route: '/projects')]
class ProjectController
{
    /**
     * GET /wp-json/starter/v1/projects
     */
    public function index(WP_REST_Request $request): WP_REST_Response
    {
        $projects = get_posts([
            'post_type' => 'project',
            'posts_per_page' => (int) ($request->get_param('per_page') ?? 10),
            'post_status' => 'publish',
        ]);

        $data = array_map(fn ($post) => [
            'id' => $post->ID,
            'title' => $post->post_title,
            'slug' => $post->post_name,
            'excerpt' => $post->post_excerpt,
        ], $projects);

        return new WP_REST_Response($data);
    }
}
