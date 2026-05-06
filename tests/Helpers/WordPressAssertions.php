<?php

declare(strict_types=1);

namespace Tests\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

trait WordPressAssertions
{
    protected function getPrefix(): string
    {
        return config('database.connections.mysql.prefix', 'wp_');
    }

    protected function siteUrl(string $path = '/'): string
    {
        return rtrim(config('app.url'), '/') . $path;
    }

    protected function httpGet(string $path, bool $followRedirects = true): \Illuminate\Http\Client\Response
    {
        $options = ['allow_redirects' => $followRedirects];

        return Http::withoutVerifying()->withOptions($options)->get($this->siteUrl($path));
    }

    /**
     * Assert that a URL renders the expected Pollora template.
     *
     * Checks for the data-pollora-template="xxx" marker in the response body,
     * proving the correct Blade view was resolved for this URL.
     */
    protected function assertTemplateIs(string $path, string $expectedTemplate, ?int $expectedStatus = null): void
    {
        $response = $this->httpGet($path);

        if ($expectedStatus !== null) {
            $this->assertEquals(
                $expectedStatus,
                $response->status(),
                sprintf('Expected status %d for %s, got %d', $expectedStatus, $path, $response->status())
            );
        }

        $this->assertStringContainsString(
            sprintf('data-pollora-template="%s"', $expectedTemplate),
            $response->body(),
            sprintf(
                'Expected template "%s" for path "%s", but marker not found in response body.',
                $expectedTemplate,
                $path
            )
        );
    }

    /**
     * Assert that a URL does NOT render a specific template.
     */
    protected function assertTemplateIsNot(string $path, string $unexpectedTemplate): void
    {
        $response = $this->httpGet($path);

        $this->assertStringNotContainsString(
            sprintf('data-pollora-template="%s"', $unexpectedTemplate),
            $response->body(),
            sprintf(
                'Did not expect template "%s" for path "%s", but it was found.',
                $unexpectedTemplate,
                $path
            )
        );
    }

    /**
     * Assert that a URL response contains a specific string.
     */
    protected function assertResponseContains(string $path, string $needle): void
    {
        $response = $this->httpGet($path);

        $this->assertStringContainsString(
            $needle,
            $response->body(),
            sprintf('Expected "%s" in response body for "%s".', $needle, $path)
        );
    }

    protected function assertWordPressTableExists(string $table): void
    {
        $fullTable = $this->getPrefix() . $table;

        $results = DB::select("SHOW TABLES LIKE '{$fullTable}'");
        $this->assertNotEmpty($results, "WordPress table '{$fullTable}' does not exist.");
    }

    protected function assertWordPressOptionEquals(string $option, string $expected): void
    {
        $result = DB::table('options')
            ->where('option_name', $option)
            ->value('option_value');

        $this->assertEquals($expected, $result, "WordPress option '{$option}' does not match expected value.");
    }

    protected function getWordPressOption(string $option): ?string
    {
        return DB::table('options')
            ->where('option_name', $option)
            ->value('option_value');
    }
}