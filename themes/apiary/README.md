# Pollora WooCommerce Theme (Apiary)

A WooCommerce theme template for [Pollora](https://pollora.dev). This repository contains placeholder files that are processed by `pollora:make-theme` when creating a new project.

## For End Users

You don't interact with this repository directly. When you create a Pollora project with this theme, it is generated automatically:

```bash
php artisan pollora:make-theme my-theme --template=apiary
```

## Contributing

### Development Setup

Theme development happens in a Pollora test project using the code name **`apiary`**. This name is unique enough to avoid accidental replacements during packaging.

1. **Develop** in `themes/apiary/` — modify views, CSS, config, etc.

2. **Package** your changes back into this repository:

```bash
cd /path/to/theme-apiary
./bin/package-theme.sh /path/to/your-project/themes/apiary
```

The script replaces all `apiary` / `Apiary` / `Theme\Apiary` references with the appropriate `%placeholder%` tokens.

3. **Review, commit, tag and push**:

```bash
git diff
git add -A && git commit -m "feat: description of changes"
git tag x.y.z
git push origin main --tags
```

### Placeholders

The following placeholders are replaced by `pollora:make-theme`:

| Placeholder | Replaced with |
|---|---|
| `apiary` | Theme slug (e.g. `my-theme`) |
| `Theme\Apiary` | PSR-4 namespace (e.g. `Theme\MyTheme`) |
| `apiary` | camelCase name (e.g. `myTheme`) |
| `https://pollora.dev` | Theme URL |
| `Pollora` | Author name |
| `https://pollora.dev` | Author URL |
| `A WooCommerce theme for Pollora` | Theme description |
| `1.0` | Version number |

### Important

- Always use `apiary` as the dev theme name — the packaging script depends on it
- Never commit files with concrete theme names (check with `grep -r "apiary" --include="*.php" --include="*.css"` before pushing)
- The `bin/` directory is excluded when the theme is downloaded by `pollora:make-theme`

## Features

- **WooCommerce**: Complete template coverage (cart, checkout, my account, orders, product pages)
- **Tailwind CSS v4**: Design tokens synced with WordPress `theme.json`
- **Alpine.js**: Lightweight interactivity (menus, modals, cart slide-over)
- **Blade templates**: Full view layer with component support
- **Accessibility**: Semantic HTML, ARIA labels, keyboard navigation
- **i18n**: Fully translatable

## License

GPL-2.0-or-later
