<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;

// The failure messages are kept: Pest's expect() has no message argument,
// so these stay PHPUnit assertions bound to the test case.

function siteHealthUrl(string $path = '/'): string
{
    return rtrim(config('app.url'), '/').$path;
}

it('returns 200 on the homepage', function (): void {
    $response = Http::withoutVerifying()->get(siteHealthUrl('/'));

    $this->assertEquals(200, $response->status(), 'Homepage did not return 200');
});

it('redirects wp-admin', function (): void {
    $response = Http::withoutVerifying()->withOptions(['allow_redirects' => false])->get(siteHealthUrl('/wp-admin/'));

    $this->assertTrue(
        in_array($response->status(), [301, 302]),
        'Expected /wp-admin/ to redirect (301 or 302), got '.$response->status()
    );
});

it('contains html tags on the homepage', function (): void {
    $response = Http::withoutVerifying()->get(siteHealthUrl('/'));
    $content = $response->body();

    $this->assertStringContainsString('<html', $content, 'Homepage does not contain <html tag');
    $this->assertStringContainsString('</html>', $content, 'Homepage does not contain </html> closing tag');
});

it('serves the homepage as html content type', function (): void {
    $response = Http::withoutVerifying()->get(siteHealthUrl('/'));

    $this->assertStringContainsString('text/html', $response->header('Content-Type'));
});
