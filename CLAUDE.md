# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with this Pollora skeleton project.

## Project Overview

Pollora is a framework that bridges Laravel and WordPress. This skeleton is the starting point for new Pollora projects, combining Laravel's architecture with WordPress's content management.

## Development Environment

### DDEV Setup

```bash
ddev start              # Start environment
ddev stop               # Stop environment
ddev ssh                # SSH into container
ddev composer [cmd]     # Run Composer commands
ddev wp [cmd]           # Run WP-CLI commands
```

WordPress is installed at `public/cms/` (configured in `wp-cli.yml`).

### Installation

```bash
# Full install (interactive)
ddev exec php artisan pollora:install

# Non-interactive install
ddev exec php artisan pollora:install --install \
    --title="My Site" --admin-user=admin \
    --admin-email=admin@example.com --admin-password=secret123 \
    --locale=en_US --public=false
```

### Theme Management

Themes are generated via `pollora:make-theme`, not manually created:

```bash
# Generate a new theme from the default template
ddev exec php artisan pollora:make-theme my-theme

# Theme is placed in themes/my-theme/ and auto-activated
```

The default theme template lives at [Pollora/theme-default](https://github.com/Pollora/theme-default). See its README for contribution guidelines.

### Building Assets

```bash
# Root assets
npm run build

# Theme assets (from theme directory)
cd themes/my-theme && npm run dev    # Dev with HMR
cd themes/my-theme && npm run build  # Production build
```

Build output goes to `public/build/theme/{theme-name}/`.

### Testing

```bash
php artisan test                    # Run all tests
php artisan test --compact          # Compact output
php artisan test --filter=testName  # Single test
```

## Architecture

### Routing

WordPress routes use the `Route::wp()` macro:

```php
Route::wp('home', fn () => view('home'));
Route::wp('single', fn () => view('post'));
Route::wp('page', fn () => view('page'));
Route::wp('404', fn () => response()->view('errors.404', [], 404));
```

A catch-all `{any}` route handled by `FrontendController` provides WordPress template hierarchy fallback.

### CSRF Exceptions

WordPress endpoints are excluded from Laravel CSRF verification in `bootstrap/app.php` since WordPress handles its own nonce-based validation.

### Blade Directives

Use [Sage Directives](https://log1x.github.io/sage-directives-docs/) for WordPress template data:

```blade
@posts
    <h2>@title</h2>
    <div>@content</div>
    <a href="@permalink">Read more</a>
    <time>@published</time>
@endposts
```


### Theme Structure

```
themes/{name}/
├── app/Providers/          # Auto-discovered service providers
├── config/                 # Theme config (menus, supports, etc.)
├── resources/
│   ├── assets/             # JS, CSS, fonts, images
│   └── views/              # Blade templates
├── functions.php           # Theme registration
├── style.css               # WP theme metadata
├── theme.json              # WP Full Site Editing config
└── vite.config.js          # Vite build config
```

## Versioning

- The skeleton follows semver aligned with Laravel major versions (Pollora 13.x → Laravel 13.x)
- See [CHANGELOG.md](CHANGELOG.md) for release history
- Themes are NOT versioned in the skeleton — they are generated at install time
- When tagging a new version, update the CHANGELOG.md (move Unreleased items to new version)

## Key Dependencies

- **Laravel 13.x** — Application framework
- **Pollora Framework ^13.0** — Laravel-WordPress bridge
- **WordPress 6.9** — CMS (in `public/cms/`)
- **Vite 8** + **laravel-vite-plugin 3** — Asset bundling
- **Tailwind CSS 4** — Styling (via `@tailwindcss/vite`)
- **Sage Directives** — WordPress Blade directives

## Related Documentation

For deeper context on specific areas, Claude Code will auto-discover these files when working in the relevant directories:

- Framework internals: @vendor/pollora/framework/CLAUDE.md
- Active theme: each theme under `themes/` has its own `CLAUDE.md`. To find the active theme, run `ddev wp theme status` or check `config/theme.php` (if `active` is set).
- Plugins: WordPress plugins in `public/content/plugins/` may include their own `CLAUDE.md` with plugin-specific guidelines.