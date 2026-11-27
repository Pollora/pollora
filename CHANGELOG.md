# Changelog

All notable changes to the Pollora skeleton will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased](https://github.com/Pollora/pollora/compare/v13.44.3...main)

### Security

- **The demo site that releases v13.43.2 to v13.44.3 installed in every new project is removed.** Among it, two REST routes open to everyone: `/wp-json/starter/v1/status` answered with the PHP and WordPress versions, the active theme and the registered post types and taxonomies, and `/wp-json/starter/v1/projects` listed projects. A project created from one of those releases keeps these files until they are deleted (see *Removed*).

### Removed

- The demo content that had been committed to the skeleton by mistake. A project created from v13.43.2 to v13.44.3 can delete what it does not use; check first that nothing of yours was built on it:
  - `app/Cms/`: everything except `.keep` and `PostTypes/Book.php` (`Cli/`, `Hooks/`, `Rest/`, `Schedule/`, `Taxonomies/`, `PostTypes/Project.php`, `Service.php`, `TestCase.php`). WordPress keeps the cron events these classes scheduled until they are unscheduled (`wp cron event list`).
  - `themes/apiary`, `themes/buzz`, `themes/pollora-starter` (a theme is generated with `php artisan pollora:make:theme`). Do not delete the active theme.
  - `Modules/BlocksDemo`, `Modules/Test` and `modules_statuses.json` (if you added modules of your own, only remove these two entries from it).
  - The `fr_FR` files of `public/content/languages/`, unless the site is in French (WordPress can download them again).
  - `.claude/`, the root `pollora` script, `tailwind.safelist.txt` and `tests/Feature/Routing/`.

## [v13.44.3](https://github.com/Pollora/pollora/compare/v13.44.2...v13.44.3) - 2026-11-26

### Security

- The lock pins **WordPress 7.1.5**, a [security release](https://wordpress.org/news/2026/11/wordpress-7-1-5-release/). v13.44.2 and the releases before it, published since 7.1.5 came out on 18 November, still installed 7.1.4. An existing project gets it with `composer update johnpbloch/wordpress --with-dependencies`.

## [v13.44.2](https://github.com/Pollora/pollora/compare/v13.44.1...v13.44.2) - 2026-11-26

### Added

- The lock pins `pollora/framework` **v13.44.2** (required at `^13.44.2`). `pollora:install` writes the eight WordPress keys and salts of a new site to `.env`; nothing generated them before. An existing project runs `php artisan pollora:salts:generate` once, which signs everyone out.

### Fixed

- `DB_CONNECTION=mariadb`, Laravel's own MariaDB driver, now works like `mysql`: the site answered *not set up yet* and `pollora:install` failed with `Undefined constant … WPINC`.

## [v13.44.1](https://github.com/Pollora/pollora/compare/v13.44.0...v13.44.1) - 2026-11-26

### Fixed

- The lock pins `pollora/framework` **v13.44.1** (required at `^13.44.1`): REST requests (`/wp-json/…`, `/?rest_route=…`) get the hooks the application's and the theme's providers add in `boot()`. WordPress answered them before those providers had booted, so a `save_post` hooked there did not fire on a block editor save, and a route registered there answered `rest_no_route`.

### Changed

- **Behaviour change in the framework, front-end pages included: check your project before updating.** The main query is now built after the application's and the theme's providers have booted.
  - Hooks on the main query (`pre_get_posts`, `parse_query`, `request`) added in a provider's `boot()` were not applied until now. They are applied now, so an existing site can show different content.
  - A provider that reads the main query (`is_*()`, `get_queried_object()`, query vars) in its `boot()` now runs before it is parsed, and so does a route file that reads it while it is loaded. Read it from a hook such as `wp`, or inside the route. A `booted()` callback is not enough: one registered in `register()`, or by a provider that boots before WordPress's, still runs before the query is parsed.
  - A project that worked around the REST problem by hooking in `register()` can move its hooks back to `boot()`; one that hooked in both gets them twice.

### Security

- The lock moves six Symfony packages to 7.4.24 (`http-foundation`, `http-kernel`, `mailer`, `mime`, `routing`, `var-dumper`), which answers the advisories published for `http-kernel`, `mailer` and `routing`. An existing project gets them with `composer update`.

## [v13.44.0](https://github.com/Pollora/pollora/compare/v13.43.4...v13.44.0) - 2026-11-24

### Changed

- **Built on Laravel 13.44.** The skeleton requires `laravel/framework` `^13.44` and `pollora/framework` `^13.44.0`, and the lock pins Laravel v13.44.1 and Pollora framework v13.44.0. Sanctum stays at `^4.3 || ^5.0`.
- The persistent object cache of the framework is `pollora/object-cache` 0.3.1: when the Composer autoloader named in its drop-in cannot be read by the web server, the drop-in stays inert and writes the path to the PHP error log (before 0.3.1 it could stop the site with a fatal error), and `pollora:object-cache:status` shows the autoloader. Run `pollora:object-cache:enable` again to update an installed drop-in.

## [v13.43.4](https://github.com/Pollora/pollora/compare/v13.43.3...v13.43.4) - 2026-11-24

### Changed

- The lock pins `pollora/framework` **v13.43.4** (required at `^13.43.4`): the `OBJECT_CACHE_*` environment variables of the persistent object cache (`OBJECT_CACHE_PREFETCH`, `OBJECT_CACHE_REDIS_DATABASE`...) work again, which v13.43.3 had dropped.

## [v13.43.3](https://github.com/Pollora/pollora/compare/v13.43.1...v13.43.3) - 2026-11-24

### Added

- **A `sqlite` connection, opt-in, for development, CI and demos (Pollora/framework#527).** `config/database.php` declares it (file `database/database.sqlite`, already ignored by `database/.gitignore` and outside `public/`, WAL journal, same table prefix as MySQL), `public/wp-config.php` reads the table prefix of the default connection instead of the `mysql` one's, and `public/content/db.php` loads the SQLite Database Integration plugin when `DB_CONNECTION=sqlite` (and tells how to install it when it is missing). Nothing changes for a project on MySQL. Set up with `composer require wp-plugin/sqlite-database-integration` then `php artisan pollora:install --db=sqlite`. Not for production. Needs the framework release that adds `--db` (`DatabaseSettings`): raise the `pollora/framework` constraint with it.

- **`public/.htaccess`: the administration of a sub-site on a sub-directory network** (Pollora/framework#674). On such a network `/site2/wp-admin/` and `/site2/wp-login.php` are not files (WordPress lives in `cms/`), so the front controller answered instead of WordPress. A documented block, **commented out by default** so that a single site is untouched, sends `wp-admin/`, `wp-includes/`, `wp-login.php`, `wp-signup.php`, `wp-activate.php`, `wp-comments-post.php`, `wp-cron.php` and `xmlrpc.php` under a one-segment site path to `cms/` and keeps the original URL; remove the leading `# ` of its four lines on a sub-directory network. Measured on Apache 2.4 with PHP-FPM and a real two-site network: login and dashboard of the sub-site, its admin assets, `/cms/` unchanged. The nginx equivalent is in the documentation.

### Changed

- The lock now pins `pollora/framework` **v13.43.3** (required at `^13.43.3`) and the packages published since v13.43.2: `pollora/media` 0.10.0 (an empty signing key is refused; the Pollora and imgproxy thumbnail drivers are aligned), `assets` 0.2.0, `discovery` 0.3.0, `object-cache` 0.3.0 (new), and the patch releases of `option` 1.1.3, `schedule` 1.0.3, `abilities` 1.0.3, `meta` 0.1.3, `query` 3.1.1, `roles` 0.2.2, `block-bindings` 0.2.2, `wp-cli` 0.1.3 and `wp-rest` 2.0.3. In-range updates of `guzzlehttp/guzzle` (8.6.0), `nwidart/laravel-modules` (13.4.0) and three smaller libraries come with them.

- The example classes of `app/Cms` are now `final` (CONV-08): `DiagnosticCommand`, `SeedCommand`, `ContentHooks`, `ThemeHooks`, `Book`, `Project`, `Service`, `TestCase` (post types), `ProjectController`, `StatusController`, `CacheCleanup`, `IntervalMaintenance`, `ProjectCategory` and `TestStatus`. Pollora discovers them by their attributes and nothing extends them; edit them in place or copy them, and write your own classes beside them. Five classes stay open on purpose, as in a stock Laravel application (`make:*` generates them open and people extend or mock them): `App\Models\User`, `App\Providers\AppServiceProvider`, `App\View\Components\AppLayout`, `Database\Factories\UserFactory` and `Database\Seeders\DatabaseSeeder`
- **No Laravel session on the WordPress routes** (`config/wordpress.php`, `'sessions' => false`). An anonymous visitor no longer starts a session (a row in the `sessions` table and two cookies on the first request), which removes a database write per visitor and lets a page cache store the response (measured in the framework: 1 `INSERT` and `Set-Cookie` with the session on, 0 query and no cookie with it off). WordPress authentication, nonces and WooCommerce do not use it. Set it back to `true` if your project adds a Laravel form (`@csrf`), `old()` or flash data on a `Route::wp()` route, or Laravel-side authentication there; Laravel-native routes keep their session either way.

### Fixed

- **`themes/pollora-starter`: the Dependabot security update for `uuid` can land again** (Pollora/harness#112, same defect as `themes/apiary`). The theme pinned the `@wordpress/blocks` 14, `block-editor` 14 and `components` 29 lines, which require `uuid@^9.0.1`, so the run "npm_and_yarn in /themes/pollora-starter for uuid" failed with "conflicting dependencies". The `@wordpress/*` packages are now at the same ranges as the `theme-default` template (`block-editor` ^20, `blocks` ^17.1, `components` ^44, `element` ^10.1, `i18n` ^7.1) and the lock resolves `uuid` 14.0.3 only. `npm audit` for the theme goes from 11 moderate findings to 2 (`glob`, `minimatch`; none high or critical).

- **`themes/apiary`: the Dependabot security update for `uuid` can land again.** The theme pinned the `@wordpress/blocks` 14, `block-editor` 14 and `components` 29 lines, which require `uuid@^9.0.1`, so Dependabot could not reach the patched `uuid` (14.x) and three runs failed with "conflicting dependencies". The `@wordpress/*` packages are now at the same ranges as the `theme-apiary` template (`block-editor` ^20, `blocks` ^17.1, `components` ^44, `element` ^10.1, `i18n` ^7.1) and the lock resolves `uuid` 14.0.3 only. `npm audit` for the theme goes from 13 moderate findings to 4 (none high or critical; `uuid` and `showdown` are gone).

- The per-container Vite hot files (`public/<name>.hot`, for instance `public/apiary.hot`) are now git-ignored (`/public/*.hot`; only `/public/hot` was), and the `public/apiary.hot` that was tracked is removed. A tracked hot file made a fresh clone render the theme in dev-server mode (assets requested from `:5173`) even with a valid `public/build/`

## [v13.43.1](https://github.com/Pollora/pollora/compare/v13.43.0...v13.43.1) - 2026-11-19

### Changed
- A new project installs `pollora/framework` **v13.43.1**, which the lock now pins (it pinned v13.43.0), and `pollora/framework` is required at `^13.43.1`. What changed is in the [framework's release notes](https://github.com/Pollora/framework/releases/tag/v13.43.1)
- The lock now pins `pollora/media` 0.9.0 (its security fixes) and `pollora/discovery` 0.2.0 (a warm cache that reads no file), with the packages that accept it: `pollora/schedule` 1.0.1, `meta` 0.1.1, `block-bindings` 0.2.1, `roles` 0.2.1, `wp-cli` 0.1.2 and `wp-rest` 2.0.1
- The quality workflow runs the harness at **v0.1.32** (it ran v0.1.31)

## [v13.43.0](https://github.com/Pollora/pollora/compare/v13.42.0...v13.43.0) - 2026-11-19

### Changed
- A new project installs `pollora/framework` **v13.43.0**, which the lock now pins (it pinned v13.42.0), with Laravel **13.43** (`laravel/framework` and `pollora/framework` are now required at `^13.43`). What changed is in the [framework's release notes](https://github.com/Pollora/framework/releases/tag/v13.43.0)
- The lock also pins the packages published with it: `pollora/ajax` 1.3.0 (AJAX handlers can require a nonce and a capability), `pollora/attributes` 0.1.4, `pollora/colt` 11.1.0, `pollora/query` 3.1.0 and `pollora/abilities` 1.0.2
- The quality workflow runs the harness at **v0.1.31** (it ran v0.1.28)

## [v13.42.0](https://github.com/Pollora/pollora/compare/v13.40.3...v13.42.0) - 2026-11-12

### Changed
- A new project installs `pollora/framework` **v13.42.0**, which the lock now pins (it pinned v13.40.3). What changed is in the [framework's release notes](https://github.com/Pollora/framework/releases/tag/v13.42.0)

## [v13.40.3](https://github.com/Pollora/pollora/compare/v13.40.2...v13.40.3) - 2026-11-10

### Changed
- A new project installs `pollora/framework` **v13.40.3**, which the lock now pins (it pinned v13.40.2). What changed is in the [framework's release notes](https://github.com/Pollora/framework/releases/tag/v13.40.3)

## [v13.40.2](https://github.com/Pollora/pollora/compare/v13.39.3...v13.40.2) - 2026-11-06

### Added

- `config/modules.php`, Pollora's modules configuration (framework v13.35.3): where a module's enabled state lives (`MODULES_CONNECTOR`, `json` by default, as before: `modules_statuses.json`), states forced by `MODULES_LOCKED_ENABLED` / `MODULES_LOCKED_DISABLED`, and who may switch modules from Plugins › Modules. nwidart/laravel-modules reads it while it registers, so these settings only apply from this file. `module:make` still writes Pollora's lean module
- A Pollora module published as a Composer package installs into `Modules/`. `composer require pollora/<module>` places a `pollora/*` package of type `laravel-library` in `Modules/{$name}/` (`composer/installers` reads the module's `installer-name` for the folder's exact case), where nwidart finds it; enabling it is still a line in `modules_statuses.json`. The rule is limited to the `pollora` vendor: other Laravel packages that declare `laravel-library` (about ninety on Packagist) keep their usual place. The rule comes last in `installer-paths`: `composer/installers` applies the first rule that matches, and a `vendor:` rule ignores the package type, so placed first it sent Pollora's own WordPress plugins (`pollora/mcp-connector`, `pollora/ai-visibility`) to `Modules/` too. Existing projects can add the same rule, last, to their `composer.json`

### Changed

- `symfony/mime` is locked at v7.4.22 (it was v7.4.19) to fix CVE-2026-45070, an email header injection through the names of MIME parameters (medium)
- WordPress 7.1.4 (`johnpbloch/wordpress` locked at 7.1.4, it was 7.1.3); the constraint `^7.1` already accepted it
- README: the organization's banner and badges (with the Tests workflow), installation first, the empty Sponsors section replaced by a pointer to GitHub Discussions, and the standard Contributing and License footer
- `global_rules.md`, a set of general coding rules that described a `src/` layout this skeleton does not have, is no longer part of the archive that `composer create-project` downloads (it stays in the repository); the `.shift` file a Laravel Shift pull request left behind is removed
- A new project installs `pollora/framework` **v13.40.2**, which the lock now pins (it pinned v13.39.3). What changed is in the [framework's release notes](https://github.com/Pollora/framework/releases/tag/v13.40.2)

### Fixed

- The development npm dependencies are updated in `package-lock.json` to fix known vulnerabilities (`concurrently` 10.0.6 brings `shell-quote` 1.12.0; `vite` 7.3.7, `follow-redirects` 1.16.2, `source-map-js` 1.2.2). `npm audit` reports none.
- `merge-plugin` no longer merges a module's `require-dev`. A module installed by Composer is also merged through `Modules/*/composer.json`, and its development tools (PHPUnit, Rector…) became requirements of the project: measured, the next `composer install` removed WordPress core from `public/cms` and the site stopped booting. `merge-dev` is now `false`; a module's `require` is still merged. Existing projects can set it in `extra.merge-plugin`

## [v13.38.1](https://github.com/Pollora/pollora/compare/v13.38.0...v13.38.1) - 2026-10-21

Pollora skeleton for framework 13.38.1: `pollora/framework` ^13.38 locked at v13.38.1, which serves the home page and `/cms/wp-admin/` under Valet and Herd, reads an `APP_URL` without a scheme and reports a document root that exposes `.env` (Pollora/framework#513).

- `LocalValetDriver.php`, read by Laravel Valet and Laravel Herd from the project root. Both send every `.php` URL to the front controller, so `wp-login.php` and `wp-admin/index.php` answered the site's 404 under Herd; the driver lets PHP run an existing file under `public/` (never under `uploads/`). Existing projects can copy the file to their root
- `config.platform.php` is `8.3.0` in `composer.json`: the lock was resolved on PHP 8.4 and held Symfony 8, which needs PHP 8.4.1, so a project served by PHP 8.3 (MAMP's default) stopped on Composer's platform check. The lock holds Symfony 7.4, which runs on 8.3 and 8.4. Existing projects can add the same setting and run `composer update "symfony/*" --with-all-dependencies`

See the [framework release](https://github.com/Pollora/framework/releases/tag/v13.38.1).

## [v13.38.0](https://github.com/Pollora/pollora/compare/v13.37.1...v13.38.0) - 2026-10-21

Pollora skeleton for framework 13.38.0: `pollora/framework` ^13.38 locked at v13.38.0 (Laravel 13.38.0) — the setup accepts an empty database password.

See the [framework release](https://github.com/Pollora/framework/releases/tag/v13.38.0).

## [v13.37.1](https://github.com/Pollora/pollora/compare/v13.37.0...v13.37.1) - 2026-10-20

Pollora skeleton for framework 13.37.1: `pollora/framework` ^13.37 locked at v13.37.1, which requires Laravel ^13.37 (locked at 13.38.0).

See the [framework release](https://github.com/Pollora/framework/releases/tag/v13.37.1).

## [v13.37.0](https://github.com/Pollora/pollora/compare/v13.36.1...v13.37.0) - 2026-10-20

Pollora skeleton for framework 13.37.0: `pollora/framework` ^13.37 locked at v13.37.0 — the media library on any Laravel disk (`pollora/media` 0.8.0) and the `Role` facade, Laravel 13.38.0.

See the [framework release](https://github.com/Pollora/framework/releases/tag/v13.37.0).

## [v13.36.1](https://github.com/Pollora/pollora/compare/v13.36.0...v13.36.1) - 2026-10-16

Pollora skeleton for framework 13.36.1: `pollora/framework` ^13.36 locked at v13.36.1 — assets, meta, roles and block-bindings are now packages of their own (attributes 0.1.1, `pollora/query` 2.0), WordPress 7.1.3.

See the [framework release](https://github.com/Pollora/framework/releases/tag/v13.36.1).

## [v13.36.0](https://github.com/Pollora/pollora/compare/v13.34.0-beta...v13.36.0) - 2026-10-14

Pollora skeleton for framework 13.36: Laravel 13.36 (Shift), `pollora/framework` ^13.36 locked at v13.36.0 (hook 2.0.1, schedule 1.0.0, discovery, attributes, wp-rest, wp-cli).

See the [framework release](https://github.com/Pollora/framework/releases/tag/v13.36.0).

## [v13.34.0-beta](https://github.com/Pollora/pollora/compare/v13.32.0-beta.9...v13.34.0-beta) - 2026-09-29

### Changed

- A new project installs `pollora/framework` **v13.34.0-beta** on **Laravel 13.34**: the framework's version tracks Laravel's, and this beta requires `illuminate/*` `^13.34`. The constraints follow — `laravel/framework` `^13.34` (was `^13.33`), `pollora/framework` `^13.34@beta` (was `^13.32@beta`). The release brings a third `pollora:make:theme` template, **Magazine** (a Full Site Editing block theme), Vite scripts enqueued as script modules after WordPress's import map (Firefox and Safari no longer lose the core blocks' modules), `error404` kept on a real 404 and cleared on Laravel routes, and a block theme's 404 answering HTTP 404. `patches.lock.json` is unchanged: the framework's patches are the same

## [v13.32.0-beta.9](https://github.com/Pollora/pollora/compare/v13.32.0-beta.8...v13.32.0-beta.9) - 2026-09-28

### Changed

- A new project installs `pollora/framework` **v13.32.0-beta.9**, which the lock now pins (it pinned beta.8). The release brings `<InnerBlocks />` in a block's Blade template, a block editor runtime that `pollora:make:block` now generates dynamic blocks on, and a lighter installed package (tests, CI and tooling configs are left out of the archive). `patches.lock.json` is unchanged: the framework's patches are the same

## [v13.32.0-beta.8](https://github.com/Pollora/pollora/compare/v13.32.0-beta.7...v13.32.0-beta.8) - 2026-09-25

### Changed

- A new project installs `pollora/framework` **v13.32.0-beta.8**, which the lock now pins (it pinned beta.7). The framework's code is unchanged; the release ships the browser test suite that now guards it — blocks, the template hierarchy, the framework's features and translations — run in Chromium on every pull request and in Firefox and WebKit every night. `patches.lock.json` is unchanged: no patch moved

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
