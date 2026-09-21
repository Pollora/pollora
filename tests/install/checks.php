<?php

declare(strict_types=1);

/**
 * The check groups of the install integration suite.
 *
 * Each group maps to one of the fixes released in v13.32.0-beta.3, and each is
 * meant to fail when its fix is reverted. That property is the point: a check
 * that cannot fail is what let fix 5 ship broken behind 1040 green tests.
 */

function wpEval(string $php): string
{
    return wp('eval '.escapeshellarg($php));
}

/**
 * Create a term if it is not there yet, and return its id either way.
 *
 * Seeding has to survive a re-run: the scenarios are meant to be repeatable
 * against the same install, not only against a pristine one.
 */
function ensureTerm(string $taxonomy, string $slug, string $name): int
{
    $existing = wpRaw("term list {$taxonomy} --slug={$slug} --field=term_id");

    if ($existing['code'] === 0 && $existing['out'] !== '') {
        return (int) $existing['out'];
    }

    return (int) wp("term create {$taxonomy} ".escapeshellarg($name)." --slug={$slug} --porcelain");
}

// ─────────────────────────────────────────────────────────────────────────────
// 1.2 — Every page type renders, and none answers 200 with an empty body
// ─────────────────────────────────────────────────────────────────────────────

/**
 * Seed the content the archive pages need.
 *
 * A fresh install carries one post, one sample page and the Uncategorized
 * term — not enough to exercise a tag archive, an author archive or a date
 * archive. Seeding is idempotent so the scenario can be re-run.
 *
 * @return array<string, string> page type => URL
 */
function seedRenderingFixtures(): array
{
    $categoryId = ensureTerm('category', 'install-tests', 'Install tests');
    ensureTerm('post_tag', 'install-tag', 'Install tag');

    // wp-cli has no current user, so a post created without --post_author
    // lands with author 0 and its author archive 404s. Seed it to a real
    // administrator, otherwise the author archive check tests nothing.
    $author = wpRaw('user list --role=administrator --field=ID --number=1');
    $authorId = $author['code'] === 0 && $author['out'] !== '' ? (int) $author['out'] : 1;

    $existing = wpRaw('post list --post_type=post --name=install-test-post --field=ID');
    $postId = $existing['code'] === 0 && $existing['out'] !== ''
        ? (int) $existing['out']
        : (int) wp('post create --post_type=post --post_title="Install test post" --post_name=install-test-post --post_status=publish --post_content="Rendered by the install integration suite." --post_author='.$authorId.' --porcelain');

    // A post seeded by an earlier run may predate the fix above.
    wp("post update {$postId} --post_author={$authorId}");

    wp("post term set {$postId} category install-tests");
    wp("post term set {$postId} post_tag install-tag");

    $pageExists = wpRaw('post list --post_type=page --name=install-test-page --field=ID');
    $pageId = $pageExists['code'] === 0 && $pageExists['out'] !== ''
        ? (int) $pageExists['out']
        : (int) wp('post create --post_type=page --post_title="Install test page" --post_name=install-test-page --post_status=publish --post_content="Rendered by the install integration suite." --porcelain');

    $urls = [
        'home' => wpEval('echo home_url("/");'),
        'single post' => wpEval("echo get_permalink({$postId});"),
        'page' => wpEval("echo get_permalink({$pageId});"),
        'category archive' => wpEval("echo get_category_link({$categoryId});"),
        'tag archive' => wpEval('echo get_term_link("install-tag", "post_tag");'),
        'author archive' => wpEval("echo get_author_posts_url({$authorId});"),
        'date archive' => wpEval("echo get_month_link(get_the_date('Y', {$postId}), get_the_date('m', {$postId}));"),
        'search results' => wpEval('echo home_url("/?s=install");'),
    ];

    // Custom post types are project-specific: cover whichever ones the install
    // actually registers with an archive, and stay quiet when there are none.
    $archive = wpEval(
        'foreach (get_post_types(["public" => true, "_builtin" => false], "names") as $t) {'
        .' $l = get_post_type_archive_link($t); if ($l) { echo $l; break; } }'
    );

    if ($archive !== '') {
        $urls['custom post type archive'] = $archive;
    }

    return array_filter($urls, fn (string $url): bool => str_starts_with($url, 'http'));
}

function checkPageRendering(): void
{
    section('1.2 — Page rendering (empty 200 bodies are failures)');

    try {
        $urls = seedRenderingFixtures();
    } catch (\Throwable $e) {
        test('Seed rendering fixtures', fn () => "could not seed content: {$e->getMessage()}");

        return;
    }

    foreach ($urls as $type => $url) {
        test("Renders: {$type}", fn () => rendersHtml(http($url)));
    }

    // A 404 must still be a rendered document. An empty 404 is the same
    // silent failure as an empty 200, one status code along.
    test('Renders: 404 page', fn () => rendersHtml(http(siteUrl('/this-url-does-not-exist-'.bin2hex(random_bytes(4)))), 404));
}

// ─────────────────────────────────────────────────────────────────────────────
// 1.3 — Rewrite rules are regenerated after a web install (fix 5)
// ─────────────────────────────────────────────────────────────────────────────

function checkRewriteRules(): void
{
    section('1.3 — Rewrite rules after install');

    // The rules are generated lazily; the first front request is what triggers
    // the flush the fix installs. Ask for the homepage before looking.
    http(siteUrl('/'));

    test('Permalink structure is not plain', function () {
        $structure = wpOption('permalink_structure');

        return ($structure !== null && $structure !== '') ? true : 'permalink_structure is empty — WordPress is on plain permalinks';
    });

    test('rewrite_rules option is populated', function () {
        $rules = wpRaw('option get rewrite_rules --format=json');

        return $rules['code'] === 0 && strlen($rules['out']) > 2
            ? true
            : 'rewrite_rules is missing or empty — archives will 404';
    });

    test('rewrite_rules contains a category rule', function () {
        $rules = wpRaw('option get rewrite_rules --format=json');

        return $rules['code'] === 0 && str_contains($rules['out'], 'category')
            ? true
            : 'no category rule in rewrite_rules — this is the fix 5 symptom';
    });

    test('Category archive answers without a 404', function () {
        $link = wpEval('$t = get_terms(["taxonomy" => "category", "hide_empty" => false, "number" => 1]); echo $t ? get_category_link($t[0]) : "";');

        if ($link === '') {
            return null;
        }

        return rendersHtml(http($link));
    });
}

// ─────────────────────────────────────────────────────────────────────────────
// 1.4 — wp_get_theme() finds the active theme (fix 4)
// ─────────────────────────────────────────────────────────────────────────────

function checkThemeResolution(): void
{
    section('1.4 — Theme root resolution');

    // The front end can render perfectly while the admin believes the theme is
    // gone: get_stylesheet_directory() goes through the theme_root filter,
    // wp_get_theme() does not. Both have to agree.
    test('wp_get_theme()->exists() is true', function () {
        $exists = wpEval('echo wp_get_theme()->exists() ? "yes" : "no";');

        return $exists === 'yes' ? true : 'wp_get_theme() cannot find the active theme — the admin will report it missing';
    });

    test('Active stylesheet directory exists on disk', function () {
        $dir = wpEval('echo get_stylesheet_directory();');

        return is_dir($dir) ? true : "get_stylesheet_directory() points at {$dir}, which does not exist";
    });

    test('wp_get_theme() and get_stylesheet_directory() agree', function () {
        $fromTheme = wpEval('echo wp_get_theme()->get_stylesheet_directory();');
        $fromHelper = wpEval('echo get_stylesheet_directory();');

        return $fromTheme === $fromHelper
            ? true
            : "wp_get_theme() says {$fromTheme}, get_stylesheet_directory() says {$fromHelper}";
    });

    test('Active theme is listed by wp_get_themes()', function () {
        $stylesheet = wpEval('echo get_stylesheet();');
        $listed = wpEval('echo implode(",", array_keys(wp_get_themes()));');

        return in_array($stylesheet, explode(',', $listed), true)
            ? true
            : "active theme {$stylesheet} is absent from the themes list ({$listed})";
    });
}

// ─────────────────────────────────────────────────────────────────────────────
// 1.5 — No theme installed: instructions in 503, never a 500 (fix 2)
// ─────────────────────────────────────────────────────────────────────────────

function checkMissingThemeGuidance(): void
{
    section('1.5 — Guidance when no theme is installed');

    $response = http(siteUrl('/'));

    test('Homepage answers 503, not 500', function () use ($response) {
        if ($response['status'] === 500) {
            return 'HTTP 500 — the "View [home] not found" regression is back';
        }

        return $response['status'] === 503 ? true : "expected HTTP 503, got {$response['status']}";
    });

    test('Instructions page carries a real document', function () use ($response) {
        $bytes = strlen(trim($response['body']));

        return $bytes > 0 && str_contains($response['body'], '</html>')
            ? true
            : "the instructions page answered {$bytes} bytes";
    });

    test('Instructions name the command that scaffolds a theme', function () use ($response) {
        return str_contains($response['body'], 'pollora:make:theme')
            ? true
            : 'the page does not name pollora:make:theme, so it does not tell the user what to do next';
    });

    test('No Laravel stack trace is exposed', function () use ($response) {
        foreach (['Whoops', 'Stack trace', 'ViewException', 'InvalidArgumentException'] as $leak) {
            if (str_contains($response['body'], $leak)) {
                return "a Laravel error page leaked: {$leak}";
            }
        }

        return true;
    });
}

// ─────────────────────────────────────────────────────────────────────────────
// 1.6 — Installing against a URL that already answers (fix 1)
// ─────────────────────────────────────────────────────────────────────────────

function checkRespondingUrlInstall(string $decoyUrl): void
{
    section('1.6 — Install against a URL that already answers');

    test('Decoy server answers with a Set-Cookie', function () use ($decoyUrl) {
        $response = http($decoyUrl, [], false);

        return stripos($response['headers'], 'set-cookie:') !== false
            ? true
            : 'the decoy did not set a cookie — this scenario cannot reproduce fix 1';
    });

    // WP_Http_Cookie is only reached when a response actually carries a cookie
    // to parse. That is the whole trigger: a site already answering on the
    // target URL made the installer fatal before the full HTTP stack was loaded.
    test('WP_Http_Cookie is available to the installer', function () {
        $available = wpEval('echo class_exists("WP_Http_Cookie") ? "yes" : "no";');

        return $available === 'yes' ? true : 'WP_Http_Cookie is not loaded — the install would fatal';
    });

    test('A cookie-setting response is parsed without a fatal', function () use ($decoyUrl) {
        $result = wpRaw('eval '.escapeshellarg(
            '$r = wp_remote_get("'.$decoyUrl.'", ["timeout" => 10]);'
            .' echo is_wp_error($r) ? "error:".$r->get_error_message() : "cookies:".count(wp_remote_retrieve_cookies($r));'
        ));

        if ($result['code'] !== 0) {
            return "wp_remote_get fataled: {$result['out']}";
        }

        if (str_starts_with($result['out'], 'error:')) {
            return $result['out'];
        }

        return str_starts_with($result['out'], 'cookies:') ? true : "unexpected output: {$result['out']}";
    });

    test('Install completed despite the responding URL', function () {
        $siteurl = wpOption('siteurl');

        return ($siteurl !== null && $siteurl !== '') ? true : 'siteurl is unset — the install did not finish';
    });
}

// ─────────────────────────────────────────────────────────────────────────────
// 1.7 — wordpress.org offers no update for the project's themes (fix 6)
// ─────────────────────────────────────────────────────────────────────────────

/**
 * Build the update offer wordpress.org sends for a theme it distributes.
 *
 * The shape matters: WordPress keys `response` and `no_update` by stylesheet
 * and reads `new_version` from the entry, so a shorter fake would be filtered
 * correctly and still tell us nothing about what the admin displays.
 *
 * @return string a PHP array literal, for embedding in a wp eval
 */
function themeUpdateOffer(string $stylesheet): string
{
    return var_export([
        'theme' => $stylesheet,
        'new_version' => '99.0.0',
        'url' => 'https://wordpress.org/themes/'.$stylesheet.'/',
        'package' => 'https://downloads.wordpress.org/theme/'.$stylesheet.'.99.0.0.zip',
        'requires' => '6.0',
        'requires_php' => '8.0',
    ], true);
}

/**
 * Fix 6: a theme scaffolded into the project must never be offered an update
 * from wordpress.org.
 *
 * The danger is a name collision. `pollora:install` generates a theme called
 * `default`, wordpress.org publishes a theme called `default`, and WordPress
 * sends every installed stylesheet to the update API keyed by its directory
 * name. Accepting the offer replaces the project's theme — and the user's
 * work — with that download.
 *
 * Only the live site can show this. The guard is a filter on
 * `site_transient_update_themes`, so what is under test is that the hook is
 * registered at all, which no unit test can see: ThemeUpdateGuardTest already
 * proves the filtering logic and passed while nothing called it.
 *
 * The control case is the half that makes this falsifiable. A guard that
 * emptied `response` wholesale would pass every "no update offered" check and
 * silently stop the site from ever hearing about a real theme update, so an
 * offer for a stylesheet outside the project's themes directory has to
 * survive.
 */
function checkThemeUpdateGuard(): void
{
    section("1.7 — wordpress.org updates for the project's themes");

    $stylesheet = wpEval('echo get_stylesheet();');

    if ($stylesheet === '') {
        test('An active theme is present', fn (): string => 'no theme is active — there is nothing this group can measure');

        return;
    }

    // A stylesheet that is deliberately not one of ours: no directory of this
    // name exists under the project's themes path, so the guard must leave it
    // alone.
    $foreign = 'twentytwentyfour';

    $seed = '$t = get_site_transient("update_themes");'
        .' $t = is_object($t) ? $t : new stdClass();'
        .' $t->response = is_array($t->response ?? null) ? $t->response : [];'
        .' $t->no_update = is_array($t->no_update ?? null) ? $t->no_update : [];'
        .' $t->checked = is_array($t->checked ?? null) ? $t->checked : [];'
        .' $t->response['.var_export($stylesheet, true).'] = '.themeUpdateOffer($stylesheet).';'
        .' $t->response['.var_export($foreign, true).'] = '.themeUpdateOffer($foreign).';'
        .' $t->checked['.var_export($stylesheet, true).'] = "1.0.0";'
        .' $t->checked['.var_export($foreign, true).'] = "1.0.0";'
        // remove_filter first: set_site_transient does not run the read
        // filter, but a stale value from an earlier run would.
        .' set_site_transient("update_themes", $t);'
        .' echo "seeded";';

    $seeded = wpEval($seed);

    test('The update transient can be seeded with a wordpress.org offer', function () use ($seeded) {
        return $seeded === 'seeded' ? true : "could not seed update_themes: {$seeded}";
    });

    // Everything below reads the transient back, which is what runs the guard.
    $read = '$t = get_site_transient("update_themes");'
        .' echo json_encode(["response" => array_keys((array) ($t->response ?? [])), "no_update" => array_keys((array) ($t->no_update ?? []))]);';

    $state = json_decode(wpEval($read), true) ?: ['response' => [], 'no_update' => []];

    test('The project theme is dropped from the update offers', function () use ($state, $stylesheet) {
        return in_array($stylesheet, $state['response'], true)
            ? "wordpress.org is offering an update for {$stylesheet} — accepting it overwrites the project's theme"
            : true;
    });

    test('The project theme is moved to no_update, not merely removed', function () use ($state, $stylesheet) {
        // Dropping it outright leaves WordPress considering the theme
        // unchecked, so it asks again on every admin page load.
        return in_array($stylesheet, $state['no_update'], true)
            ? true
            : "{$stylesheet} is in neither list — WordPress will keep re-checking it on every admin page";
    });

    test('An update for a theme outside the project is left alone', function () use ($state, $foreign) {
        return in_array($foreign, $state['response'], true)
            ? true
            : "the guard also swallowed {$foreign} — the site would never hear about a real theme update";
    });

    test('wp-admin counts no theme update for the project theme', function () use ($stylesheet) {
        $updates = wpEval(
            'require_once ABSPATH."wp-admin/includes/update.php";'
            .' echo implode(",", array_keys(get_theme_updates()));'
        );

        return in_array($stylesheet, array_filter(explode(',', $updates)), true)
            ? "wp-admin still lists an update for {$stylesheet}"
            : true;
    });

    test('wp theme list reports no available update', function () use ($stylesheet) {
        $update = wpRaw('theme list --name='.escapeshellarg($stylesheet).' --field=update');

        return $update['code'] === 0 && trim($update['out']) === 'none'
            ? true
            : "wp theme list says the update column is '{$update['out']}' for {$stylesheet}";
    });

    // The seeded transient is fiction. Drop it so the site goes back to
    // whatever WordPress decides on its own.
    wpEval('delete_site_transient("update_themes"); echo "cleaned";');
}
