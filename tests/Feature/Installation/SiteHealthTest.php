<?php

declare(strict_types=1);

namespace Tests\Feature\Installation;

use Tests\TestCase;

class SiteHealthTest extends TestCase
{
    public function test_homepage_returns_200(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_wp_admin_is_not_publicly_accessible(): void
    {
        $response = $this->get('/wp-admin/');

        // In Pollora, wp-admin is handled by WordPress directly (not Laravel routing),
        // so it returns 301/302 (redirect to login) or 404 depending on the test environment.
        $this->assertTrue(
            in_array($response->getStatusCode(), [301, 302, 404]),
            'Expected /wp-admin/ to redirect or return 404, got ' . $response->getStatusCode()
        );
    }

    public function test_homepage_contains_html_tags(): void
    {
        $response = $this->get('/');
        $content = $response->getContent();

        $this->assertStringContainsString('<html', $content, 'Homepage does not contain <html tag');
        $this->assertStringContainsString('</html>', $content, 'Homepage does not contain </html> closing tag');
    }

    public function test_homepage_content_type_is_html(): void
    {
        $response = $this->get('/');

        $this->assertStringContainsString('text/html', $response->headers->get('Content-Type'));
    }
}
