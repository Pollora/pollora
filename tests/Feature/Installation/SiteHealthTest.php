<?php

declare(strict_types=1);

namespace Tests\Feature\Installation;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SiteHealthTest extends TestCase
{
    private function siteUrl(string $path = '/'): string
    {
        return rtrim(config('app.url'), '/') . $path;
    }

    public function test_homepage_returns_200(): void
    {
        $response = Http::withoutVerifying()->get($this->siteUrl('/'));

        $this->assertEquals(200, $response->status(), 'Homepage did not return 200');
    }

    public function test_wp_admin_redirects(): void
    {
        $response = Http::withoutVerifying()->withOptions(['allow_redirects' => false])->get($this->siteUrl('/wp-admin/'));

        $this->assertTrue(
            in_array($response->status(), [301, 302]),
            'Expected /wp-admin/ to redirect (301 or 302), got ' . $response->status()
        );
    }

    public function test_homepage_contains_html_tags(): void
    {
        $response = Http::withoutVerifying()->get($this->siteUrl('/'));
        $content = $response->body();

        $this->assertStringContainsString('<html', $content, 'Homepage does not contain <html tag');
        $this->assertStringContainsString('</html>', $content, 'Homepage does not contain </html> closing tag');
    }

    public function test_homepage_content_type_is_html(): void
    {
        $response = Http::withoutVerifying()->get($this->siteUrl('/'));

        $this->assertStringContainsString('text/html', $response->header('Content-Type'));
    }
}
