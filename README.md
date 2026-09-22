<p align="center">
  <a href="https://pollora.dev">
    <img src="resources/images/pollora-logo.svg" width="400" alt="Pollora">
  </a>
</p>

<p align="center">
  <a href="https://packagist.org/packages/pollora/pollora"><img src="https://img.shields.io/packagist/v/pollora/pollora?include_prereleases" alt="Latest Version"></a>
  <a href="https://packagist.org/packages/pollora/pollora"><img src="https://img.shields.io/packagist/dt/pollora/pollora" alt="Total Downloads"></a>
  <a href="https://packagist.org/packages/pollora/pollora"><img src="https://img.shields.io/packagist/l/pollora/pollora" alt="License"></a>
</p>

## About Pollora

Pollora bridges **Laravel** and **WordPress**, combining Laravel's architecture with WordPress's content management. It provides the best of both worlds for building modern web applications:

- **WordPress routing** with `Route::wp()` and template hierarchy support
- **PHP attributes** for hooks, post types, taxonomies, scheduling, and REST routes
- **Blade templates** with [Sage Directives](https://log1x.github.io/sage-directives-docs/) (`@title`, `@content`, `@permalink`...)
- **Eloquent ORM** with WordPress models via [Colt](https://github.com/Pollora/colt)
- **Multi-theme support** with parent/child themes and Vite asset bundling
- **Auto-discovery** for service providers, hooks, and components
- **Gutenberg** block pattern and category management
- Seamless [database migrations](https://laravel.com/docs/migrations), [sessions](https://laravel.com/docs/session), and [cache](https://laravel.com/docs/cache)

## Documentation

Full documentation is available at **[pollora.dev](https://pollora.dev)**, starting with the [installation guide](https://pollora.dev/getting-started/installation/).

## Quick Start

```bash
composer create-project pollora/pollora my-project
cd my-project
# Configure your .env with database credentials, then:
php artisan pollora:install
```

See [CLAUDE.md](CLAUDE.md) for detailed development setup with DDEV.

## Requirements

- PHP ^8.3
- Composer 2.x
- Node.js 20+ & npm
- A database (MySQL, MariaDB, or SQLite)

## Sponsors

We extend our heartfelt gratitude to our sponsors for supporting Pollora's development. If you're interested in sponsoring, contact [olivier@amphibee.fr](mailto:olivier@amphibee.fr).

## Changelog

All notable changes are documented in the [CHANGELOG](CHANGELOG.md).

## Contributing

Considering a contribution to Pollora? See the [contribution guide](CONTRIBUTING.md).
Branch from `main` — this repository has no `develop` branch. Participation is
covered by our [Code of Conduct](CODE_OF_CONDUCT.md).

## Security

If you discover a security vulnerability, please report it privately via [GitHub Security Advisories](https://github.com/Pollora/pollora/security/advisories/new) rather than opening an issue. See [SECURITY.md](SECURITY.md) for what to include and what to expect.

## License

Pollora is open-sourced software licensed under the [MIT license](LICENSE).
