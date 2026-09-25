# Changelog

All notable changes to the Pollora skeleton will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased](https://github.com/Pollora/pollora/compare/v13.32.0-beta.7...main)

### Fixed
- The redirect from `/x/` to `/x` in `public/.htaccess` keeps the scheme, the method and the REST API intact. It is Laravel's "Redirect Trailing Slashes" rule, and it stays: URLs carry no trailing slash. But behind a proxy that terminates TLS — DDEV's router, a load balancer — Apache only sees HTTP, so it sent https visitors to `http://`; it redirected every method, so a POST or an OPTIONS to a URL ending in a slash was lost; and it redirected the REST index, `/wp-json/`, which is what `rest_url()` returns. Measured on a site: `/shop/` went to `http://…/shop`, and the block editor's permission check, `OPTIONS /wp-json/wp/v2/posts/`, was redirected to `http://` and blocked by the browser as mixed content. The rule now redirects GET and HEAD only, to the scheme the request arrived on (`HTTPS`, or `X-Forwarded-Proto: https`), and leaves `/wp-json` to WordPress. Existing projects can replace the four lines under `# Redirect Trailing Slashes If Not A Folder...` with the new block. The install tests check the redirect's target and that the REST API answers without one
- `composer update` brings a changed framework patch into an existing project. composer-patches 2 reads `patches.lock.json` alone once it exists, and patches a package only when that package is installed: measured, with a lock missing the WordPress core patch, `composer install` and `composer update pollora/framework` both exit 0 and leave WordPress unpatched — two functions named `__()`. `post-update-cmd` now runs `composer patches-relock`, then `composer patches-repatch`, before anything else. Measured on the same stale lock: WordPress is patched again and the lock lists the patch; on a healthy lock, `composer update` leaves `patches.lock.json` byte for byte. The cost is one reinstall of the WordPress core from Composer's cache per update; the site's `wp-config.php` and content live outside it

## [v13.32.0-beta.7](https://github.com/Pollora/pollora/compare/v13.32.0-beta.6...v13.32.0-beta.7) - 2026-09-24

### Added
- `patches.lock.json`, the lock composer-patches 2 keeps of every patch it applies, with its URL and SHA-256. Shipping it makes the patching of a new project deterministic and checked: measured, an install whose patch does not match the recorded checksum fails with `Hash mismatch` instead of applying something else. The patch URLs point at the framework commit that produced each patch, so the checksums do not move
- A test that WordPress core carries the framework's l10n patch, which renames WordPress's `__()` to `__wp()` so Laravel's helper can have the name. Nothing checked that it landed, and it can fail to in silence: composer-patches skips a patch that does not apply with a warning, not an error. A WordPress release that moves the hunk, a patch URL that stops answering, or a composer-patches upgrade that stops honouring `enable-patching` would each have installed a site with two functions named `__()`, with every other test green. It runs in the framework's CI too, through the Skeleton install job
- An `Assets` CI job, on both Node floors, running `npm ci` then `npm run build`. No workflow ran Node before this, which is why a broken lockfile and seven advisories could sit on `main` undisturbed. `npm ci` is deliberate: `npm install` would have repaired the lock and reported success
- `CODE_OF_CONDUCT.md` — the Contributor Covenant 2.1, with enforcement at the address the contribution guide already publishes
- Issue templates for bugs and feature requests, and a pull request template. The contribution guide had been pointing at "the bug report template provided by the repository" while no template existed. The bug form asks for the two things that most shorten a hunt: the exact package versions, and whether `/wp-login.php` shows the problem too — if it does, the cause is in the boot rather than the theme, the template or WooCommerce
- The guide documents that every install scenario but `checks` drops the database, and that `POLLORA_INSTALL_TESTS=1` is the confirmation guarding it. That was discoverable only by reading `tests/install/install.php`, which is late to find out

### Changed
- A new project installs `pollora/framework` **v13.32.0-beta.7**, which the lock now pins (it pinned beta.6). The blocks of themes, plugins and modules exist in the block editor, on the page and in the REST API — before, they existed in WP-CLI only — and preview in the editor as the page renders them. It brings `cweagans/composer-patches` **2.0.0**, the plugin applying the WordPress l10n patch
- The security policy covers this repository. It was nine lines that sent every report to the **framework's** advisory page, so a vulnerability in the skeleton itself — the installer, the shipped configuration, the application shell — had no private channel of its own, and a reporter got no supported versions, no expectation of a reply, and no idea what to include. The framework routing is kept, and widened: report to either repository rather than let the question of which one delay you
- The contribution guide is called `CONTRIBUTING.md`, which is the only spelling GitHub recognises — as `CONTRIBUTE.md` it was never linked from the sidebar when someone opened an issue or a pull request, so the one document a newcomer needs was the one they had to go looking for. It now answers the three questions a first contribution runs into: which repository owns the change, which branch to target, and how to tell the change works
- The `develop` branch is gone. It had carried nothing of its own since 2026-04-16 and sat 204 commits behind `main`, while the guide told contributors the project follows Gitflow — so a contributor who knew Gitflow was invited to branch from a five-month-old base and open a pull request against a dead branch. Releases are cut on `release/*` and merged into `main`; `main` is the base. The framework keeps its `develop` on purpose: it is installed as a dependency and needs a pre-release line others can require as `dev-develop`, which a project template has no use for

### Removed
- The `enable-patching` option. composer-patches 1 read it; version 2 applies dependency patches unconditionally and ignores it
- `yarn.lock`. The repository installs with npm — the README requires it, `composer setup` runs `npm install`, and no workflow, script or document mentions yarn anywhere — so nothing had installed from this file in a long time. It had also drifted: the security update of 2026-04-22 touched `package-lock.json` only, leaving `yarn.lock` pinning `axios` 1.8.2 where the real lock had moved to 1.15.2. A lockfile nobody installs from still gets scanned, and this one was the source of **35 of the repository's 56 Dependabot alerts**, including one of the two criticals — alerts about packages no machine has installed, drowning the ones that describe something real

### Fixed
- `npm ci` works. The committed `package-lock.json` disagreed with itself — `npm error Invalid: lock file's emoji-regex@8.0.0 does not satisfy emoji-regex@10.6.0` — so a fresh clone could not install with the one command that installs exactly what the lock says. `composer setup` runs `npm install`, which repairs such a lock in silence, and CI ran no Node at all, so nothing was in a position to notice
- Every open npm advisory on the dependency tree that is actually installed: `axios`, `vite`, `postcss`, `nanoid`, `esbuild`, `shell-quote` and `form-data` all move to a patched version. `npm audit` goes from 7 vulnerabilities (1 critical, 5 high, 1 low) to **0**. The declared floor for `axios` moves from `^1.8.2` to `^1.20.0`, because the old range still admitted the vulnerable version it named
- The README asked for "Node.js 20+". Vite 7 requires `^20.19.0 || >=22.12.0`, so Node 20.0 through 20.18 would fail the build the README implied they could run

## [v13.32.0-beta.6](https://github.com/Pollora/pollora/compare/v13.32.0-beta.4...v13.32.0-beta.6) - 2026-09-22

### Changed
- Locked on `pollora/framework` v13.32.0-beta.6. The skeleton stayed on beta.4 while the framework shipped beta.5 and beta.6, and that gap was not cosmetic: `composer create-project` installs from the `composer.lock` the skeleton ships, not from the `^13.32@beta` constraint in its `composer.json`. Measured — `composer create-project pollora/pollora:v13.32.0-beta.4` puts v13.32.0-beta.4 in `vendor/`, never the newest beta. Every project created from the last skeleton tag therefore went without the discovery fix of beta.5 and the theme-URI fixes of beta.6. No skeleton file changed between the two tags; only the lock moves, and the two version numbers travel together again
- The framework brought two releases in between. v13.32.0-beta.5 dresses the login screen from the theme's own `theme.json`, strictly opt-in behind a theme's `config/login.php`, and stops attribute discovery from walking a plugin's `node_modules` — 69,741 files per request on a plugin with a Vite build, a home page at 3.0s. v13.32.0-beta.6 makes `get_theme_file_uri()` answer a URL instead of an empty string, and keeps the server's filesystem path out of the public `<head>`. See the [framework changelog](https://github.com/Pollora/framework/blob/main/CHANGELOG.md) for both

## [v13.32.0-beta.4](https://github.com/Pollora/pollora/compare/v13.32.0-beta.3...v13.32.0-beta.4) - 2026-09-22

### Changed
- Locked on `pollora/framework` v13.32.0-beta.4. Two assertions of the HTTP integration suite had been failing on the locked framework since they were written: the login page answering 200 with a form, and the install root serving no template source. Both required a fix that only `develop` carried, so `Install (locked)` was red on `main` and every pull request inherited it — which is how a genuinely broken branch stops being distinguishable from a healthy one

### Added
- The install scenarios cover the three fixes that had none. Fix 1 is now reproduced rather than approximated: `APP_URL` is pointed at the decoy so `wp_install()` really fetches a server that already answers with a cookie — no DNS hijacking needed, since `WP_HOME` and `WP_SITEURL` are built from it. Fix 3 is pinned by both its mechanism and its symptom, measured together on a live site: reverting it flips the finder order and the category archive goes from 34 094 bytes to 0. Fix 4's second themes directory is pinned by putting a theme in `WP_CONTENT_DIR/themes` and checking it cannot displace the active one
- The integration suite reads the framework's template marker, so its fourteen template assertions run on any theme rather than only on one that annotates its own views. They assert the hierarchy chain — `category → archive → index` — instead of naming a single file, because theme-default ships none of those and correctly falls through

### Fixed
- The integration suite can fail. It had 48 silent skips, eleven of which stepped aside on a 404 — the very symptom those tests exist to catch, since an archive answering 404 is what a missing rewrite flush looks like. The 404 skips are gone; what still cannot run is listed by name under the reason that disabled it, and `POLLORA_INTEGRATION_STRICT=1` turns those into failures

## [v13.32.0-beta.3](https://github.com/Pollora/pollora/compare/v13.32.0-beta.2...v13.32.0-beta.3) - 2026-09-21

### Added
- Install integration scenarios in `tests/install`, run with `composer test:install <scenario>`. Four of the six fixes in v13.32.0-beta.3 came from the WordPress web installer, a path no test walked — and the first version of the rewrite-rules fix was inoperative while 1040 unit tests were green. Each scenario installs a site its own way and then asserts against it: `web` (the wizard, then page rendering, rewrite rules and theme resolution), `no-theme` (the wizard with `themes/` empty, which must answer 503 with instructions rather than 500), `decoy` (installing while the target URL already answers with a cookie) and `artisan` (the `pollora:install` baseline). `checks` runs the assertions against a site as it stands, without reinstalling
- A page is only counted as rendered when it answers with a document. The theme's `index.php` stub used to answer 200 with zero bytes — no error, no content, invisible to any status-code check — so every page type is fetched and an empty or truncated body fails
- `.ddev/docker-compose.decoy.yaml`, a small web server that answers with a `Set-Cookie`, reachable as `http://decoy`. Installing against a URL that already answers is what made the installer fatal on `WP_Http_Cookie`, and the cookie is what reproduces it
- CI runs each scenario against both the locked framework and its `develop` branch

### Changed
- Locks [`pollora/framework` v13.32.0-beta.3](https://github.com/Pollora/framework/releases/tag/v13.32.0-beta.3), and installs [`pollora/theme-default` v1.4.0](https://github.com/Pollora/theme-default/releases/tag/v1.4.0) — whose templates follow the WordPress hierarchy, which this release needs since `routes/web.php` no longer declares routes on the theme's behalf
- `tests/integration.php`, the HTTP suite, is now part of the repository and runs with `composer test:integration`; CI runs it after the install. It was written against a skeleton carrying demo content — `project` and `service` post types, a `project-category` taxonomy, a module route and a `starter/v1` REST namespace — none of which ships here, so the tests that need them now skip instead of failing. Its base URL comes from `POLLORA_TEST_URL`
- `routes/web.php` no longer declares WordPress routes. Its four `Route::wp()` entries — `home`, `single` → `post`, `page`, `404` — took priority over the template hierarchy, so a theme's own naming never applied: an article rendered whatever `view('post')` resolved to, and a theme naming its templates after the hierarchy (`single.blade.php`) was ignored. Anything they did not cover — categories, tags, authors, dates, search, custom post types — had no route at all and already fell through to the hierarchy, which is where every request belongs. `Route::wp()` stays available for requests that need controller logic, middleware or a named route

## [v13.32.0-beta.2](https://github.com/Pollora/pollora/compare/v13.32.0-beta...v13.32.0-beta.2) - 2026-09-17

### Added
- [pollora.dev](https://pollora.dev) as the project website and documentation, in `composer.json` (`homepage`, `support`) and the README

### Fixed
- A fresh install no longer answers 500: `.env.example` sets `CACHE_STORE=database`, but no migration created the `cache` table, so discovery and the theme's view composers failed on the first request. Adds Laravel's `cache` and `cache_locks` migration

### Changed
- Locks [`pollora/framework` v13.32.0-beta.2](https://github.com/Pollora/framework/releases/tag/v13.32.0-beta.2), which completes `pollora:install` without interaction, keeps the former command names as aliases and renders Gutenberg blocks from `resources/views/blocks` with Blade
- CI installs the skeleton from scratch under DDEV (PHP 8.4, MariaDB 10.11, Apache — the stack of the local test install) and runs the installation tests, on every push, pull request and nightly, against both the locked framework and its `develop` branch. The previous workflow only ran on `master` and `*.x` with PHP 8.1/8.2, so it never ran

## [v13.32.0-beta](https://github.com/Pollora/pollora/compare/v13.4.0...v13.32.0-beta) - 2026-09-17

The skeleton now follows Laravel's version numbers, like [`pollora/framework`](https://github.com/Pollora/framework/releases/tag/v13.32.0-beta).

### Added
- `LICENSE` file with the MIT terms `composer.json` already declared

### Changed
- Requires `pollora/framework` `^13.32@beta`, Laravel 13.32 and WordPress 7.1
- Composer scripts use the renamed `pollora:env:setup` command (was `pollora:env-setup`); `pollora:make-theme` is now `pollora:make:theme`
- Plugins and themes are installed from [wp-packages](https://repo.wp-packages.org) (`wp-plugin/*`, `wp-theme/*`) instead of wpackagist
- `composer dev` runs `php artisan dev`
- `Illuminate\Support\Carbon` used instead of `Carbon\Carbon`

### Removed
- The "Silence is golden" `index.php` from `public/content/plugins`

## [v13.4.0](https://github.com/Pollora/pollora/compare/v13.0.0...v13.4.0) - 2026-05-13

### Added
- Default theme redesign with Pollora branding (gradient hero, feature cards, latest posts)
- Menu fallback with published pages when no WordPress menu is configured
- `entry-content` CSS styles for WordPress blocks (blockquotes, links, headings, lists)
- Mobile hamburger menu with toggle
- `@query` directive for latest posts on homepage

### Changed
- Replaced `@loop`/`@endloop` with `@posts`/`@endposts` (Sage Directives)
- Replaced `Loop::` facade calls with Sage Directives (`@title`, `@content`, `@permalink`, `@published`)
- Links styled via `theme.json` (no underline by default, foreground color)
- Updated `theme.json` color palette to Pollora brand colors

### Fixed
- Blockquote/citation styling for WordPress `wp-block-quote` blocks
- Button hover states no longer overridden by WordPress global link styles (using `wp-element-button`)

## [v13.0.0](https://github.com/Pollora/pollora/compare/v12.0.0...v13.0.0) - 2026-04-20

### Added
- E2E installation tests (EnvironmentSetupTest, WordPressInstallTest, SiteHealthTest)
- `WordPressAssertions` trait for reusable WP database assertions
- WordPress hybrid routing (`Route::wp('home')`, `Route::wp('single')`, `Route::wp('page')`, `Route::wp('404')`)
- 404 error page with Pollora branding
- `composer.local.json` support in `.gitignore` for local development overrides

### Changed
- **BREAKING**: Upgraded to Laravel 13 (`laravel/framework ^13.5`)
- **BREAKING**: Requires `pollora/framework ^13.0`
- Upgraded to `laravel/tinker ^3.0`, `laravel/sanctum ^4.3 || ^5.0`
- Upgraded to Vite 7 and `laravel-vite-plugin ^2.0`
- Adopted PHP 8.3+ attributes for class properties (Laravel Shift)
- Added `composer run setup` and `composer run test` scripts
- Removed default Laravel welcome view in favor of WordPress routing
- Removed legacy `MyTheme`/`Pollora` theme providers

### Removed
- `resources/views/welcome.blade.php` (replaced by `Route::wp('home')`)
- `tests/Feature/ExampleTest.php` (replaced by Installation tests)

## [v12.0.0](https://github.com/Pollora/pollora/releases/tag/v12.0.0) - 2026-04-20

Initial tagged release of the Pollora skeleton targeting Laravel 12.

### Features
- Laravel 12 + WordPress 6.9 integration via Pollora Framework
- Default theme with Blade templates and Sage Directives
- DDEV development environment support
- Custom post types and taxonomies via config files
- Hybrid routing with `Route::wp()` macro
- Meta Box integration for custom fields and Gutenberg blocks