<?php

declare(strict_types=1);

/**
 * Shared helpers for the install integration scenarios.
 *
 * These tests exist because four of the six fixes shipped in v13.32.0-beta.3
 * came from the web install path, which no test walked. One of them was
 * inoperative while 1040 unit tests were green: only a real install caught it.
 * So everything here talks to a real site over HTTP, or to WordPress through
 * wp-cli — never to a mock.
 */

final class Results
{
    public static int $passed = 0;
    public static int $failed = 0;
    public static int $skipped = 0;
}

function section(string $title): void
{
    echo "\n\033[1m── {$title} ──\033[0m\n";
}

/**
 * Run one assertion.
 *
 * The callback returns true (pass), false (fail), null (skip), or a string,
 * which fails the test and is shown as the reason. A string beats a bare
 * false: when a page type renders empty we want to read the status and the
 * byte count, not just "✗".
 */
function test(string $name, callable $fn): void
{
    try {
        $result = $fn();
    } catch (\Throwable $e) {
        echo "  \033[31m✗\033[0m  {$name} — {$e->getMessage()}\n";
        Results::$failed++;

        return;
    }

    if ($result === null) {
        echo "  \033[33m•\033[0m  {$name} \033[2m(skipped)\033[0m\n";
        Results::$skipped++;

        return;
    }

    if ($result === true) {
        echo "  \033[32m✓\033[0m  {$name}\n";
        Results::$passed++;

        return;
    }

    $reason = is_string($result) ? " — {$result}" : '';
    echo "  \033[31m✗\033[0m  {$name}{$reason}\n";
    Results::$failed++;
}

function summary(): int
{
    echo "\n\033[1m══════════════════════════════════════════\033[0m\n";
    $color = Results::$failed > 0 ? '31' : '32';
    echo "  \033[{$color}mPassed: ".Results::$passed.'  |  Failed: '.Results::$failed.'  |  Skipped: '.Results::$skipped."\033[0m\n";
    echo "\033[1m══════════════════════════════════════════\033[0m\n\n";

    return Results::$failed > 0 ? 1 : 0;
}

// ─────────────────────────────────────────────────────────────────────────────
// HTTP
// ─────────────────────────────────────────────────────────────────────────────

/**
 * @param  array<string, string>  $post  when non-empty, sends a POST
 * @return array{status:int, body:string, headers:string}
 */
function http(string $url, array $post = [], bool $followRedirects = true): array
{
    $ch = curl_init($url);
    $options = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => true,
        CURLOPT_FOLLOWLOCATION => $followRedirects,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_USERAGENT => 'pollora-install-tests',
    ];

    if ($post !== []) {
        $options[CURLOPT_POST] = true;
        $options[CURLOPT_POSTFIELDS] = http_build_query($post);
    }

    curl_setopt_array($ch, $options);

    $response = curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $headerSize = (int) curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($response === false) {
        throw new \RuntimeException("request to {$url} failed: {$error}");
    }

    return [
        'status' => $status,
        'headers' => substr($response, 0, $headerSize),
        'body' => substr($response, $headerSize),
    ];
}

/**
 * The assertion that matters most in this suite.
 *
 * Fix 3 shipped because the theme's `index.php` stub answered 200 with zero
 * bytes: no error, no content, invisible to any status-code-only check. A page
 * is only considered rendered when it answers with the expected status *and*
 * carries a real HTML document.
 */
function rendersHtml(array $response, int $expectedStatus = 200): bool|string
{
    if ($response['status'] !== $expectedStatus) {
        return "expected HTTP {$expectedStatus}, got {$response['status']}";
    }

    $bytes = strlen(trim($response['body']));

    if ($bytes === 0) {
        return "HTTP {$response['status']} with an empty body";
    }

    if (! str_contains($response['body'], '</html>')) {
        return "HTTP {$response['status']} with {$bytes} bytes but no closing </html> — truncated or not a document";
    }

    foreach (['Fatal error', 'Parse error', 'Uncaught', 'Warning:', 'Notice:', 'Deprecated:'] as $noise) {
        if (str_contains($response['body'], $noise)) {
            return "PHP output leaked into the page: {$noise}";
        }
    }

    return true;
}

// ─────────────────────────────────────────────────────────────────────────────
// WordPress, through wp-cli
// ─────────────────────────────────────────────────────────────────────────────

/**
 * @return array{code:int, out:string}
 */
function wpRaw(string $args): array
{
    $descriptors = [1 => ['pipe', 'w'], 2 => ['pipe', 'w']];
    $process = proc_open('wp '.$args.' 2>&1', $descriptors, $pipes, base_path());

    if (! is_resource($process)) {
        throw new \RuntimeException("could not run: wp {$args}");
    }

    $out = stream_get_contents($pipes[1]) ?: '';
    fclose($pipes[1]);
    fclose($pipes[2]);
    $code = proc_close($process);

    return ['code' => $code, 'out' => trim($out)];
}

function wp(string $args): string
{
    $result = wpRaw($args);

    if ($result['code'] !== 0) {
        throw new \RuntimeException("wp {$args} failed: {$result['out']}");
    }

    return $result['out'];
}

function wpOption(string $name): ?string
{
    $result = wpRaw("option get {$name}");

    return $result['code'] === 0 ? $result['out'] : null;
}

function base_path(): string
{
    return dirname(__DIR__, 2);
}

/**
 * The site URL, asked of WordPress itself once it is installed.
 *
 * Before an install there is nothing to ask, so the caller passes it in
 * through POLLORA_TEST_URL (the CI workflow does). No .env parsing here.
 */
function siteUrl(string $path = '/'): string
{
    static $base = null;

    if ($base === null) {
        $base = getenv('POLLORA_TEST_URL') ?: wpOption('home');

        if (! $base) {
            throw new \RuntimeException('cannot determine the site URL: set POLLORA_TEST_URL');
        }

        $base = rtrim($base, '/');
    }

    return $base.$path;
}
