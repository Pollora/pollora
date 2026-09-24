<?php

declare(strict_types=1);

namespace Tests\Helpers;

use Illuminate\Http\Client\Response;
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
        return rtrim(config('app.url'), '/').$path;
    }

    protected function httpGet(string $path, bool $followRedirects = true): Response
    {
        $options = ['allow_redirects' => $followRedirects];

        return Http::withoutVerifying()->withOptions($options)->get($this->siteUrl($path));
    }

    /**
     * The template that rendered a response, read from the marker the framework
     * writes under WP_DEBUG: <!-- pollora:template="single" path="themes/x/resources/views/single.blade.php" -->
     *
     * @return array{template: string, path: string}|null
     */
    protected function renderedTemplate(string $body): ?array
    {
        if (! preg_match('/<!-- pollora:template="([^"]+)" path="([^"]+)" -->/', $body, $matches)) {
            return null;
        }

        return ['template' => $matches[1], 'path' => $matches[2]];
    }

    /**
     * Assert that a URL renders the expected Pollora template.
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

        $this->assertSame(
            $expectedTemplate,
            $this->requireRenderedTemplate($path, $response->body())['template'],
            sprintf('Unexpected template for path "%s".', $path)
        );
    }

    /**
     * Assert that a URL does NOT render a specific template.
     *
     * A response without the marker fails: it proves nothing either way.
     */
    protected function assertTemplateIsNot(string $path, string $unexpectedTemplate): void
    {
        $response = $this->httpGet($path);

        $this->assertNotSame(
            $unexpectedTemplate,
            $this->requireRenderedTemplate($path, $response->body())['template'],
            sprintf('Did not expect template "%s" for path "%s".', $unexpectedTemplate, $path)
        );
    }

    /**
     * Assert that a URL renders the first template of a hierarchy chain that the
     * responding theme actually ships, so the assertion follows the theme on disk.
     *
     * @param  list<string>  $chain
     */
    protected function assertTemplateIsFirstShippedOf(string $path, array $chain, ?int $expectedStatus = null): void
    {
        $response = $this->httpGet($path);
        $rendered = $this->requireRenderedTemplate($path, $response->body());
        $expectedTemplate = $this->firstShippedTemplate($rendered['path'], $chain);

        $this->assertNotNull(
            $expectedTemplate,
            sprintf('The theme rendering "%s" ships none of: %s.', $path, implode(', ', $chain))
        );

        $this->assertTemplateIs($path, $expectedTemplate, $expectedStatus);
    }

    /**
     * Whether the theme that rendered a template ships a view for the given name.
     */
    protected function themeShipsTemplate(string $path, string $template): bool
    {
        $rendered = $this->requireRenderedTemplate($path, $this->httpGet($path)->body());

        return $this->firstShippedTemplate($rendered['path'], [$template]) === $template;
    }

    /**
     * @param  list<string>  $chain
     */
    private function firstShippedTemplate(string $renderedPath, array $chain): ?string
    {
        if (! preg_match('#^(themes/[^/]+/resources/views)/#', $renderedPath, $matches)) {
            return null;
        }

        foreach ($chain as $template) {
            if (is_file(base_path("{$matches[1]}/{$template}.blade.php"))) {
                return $template;
            }
        }

        return null;
    }

    /**
     * @return array{template: string, path: string}
     */
    private function requireRenderedTemplate(string $path, string $body): array
    {
        $rendered = $this->renderedTemplate($body);

        $this->assertNotNull(
            $rendered,
            sprintf('No pollora:template marker in the response for "%s" (is WP_DEBUG on?).', $path)
        );

        return $rendered;
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
        $fullTable = $this->getPrefix().$table;

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
