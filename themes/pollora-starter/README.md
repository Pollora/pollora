# Pollora Default Theme

The default theme template for [Pollora](https://github.com/Pollora/framework). This repository contains placeholder files that are processed by `pollora:make-theme` when creating a new project.

## For End Users

You don't interact with this repository directly. When you create a Pollora project, the theme is generated automatically:

```bash
composer create-project pollora/pollora my-project
# or manually:
php artisan pollora:make-theme my-theme
```

## Contributing

### Development Setup

Theme development happens in a Pollora test project using the code name **`pollora-starter`**. This name is unique enough to avoid accidental replacements during packaging.

1. **Generate the dev theme** in your test project:

```bash
php artisan pollora:make-theme pollora-starter \
    --theme-author="Pollora" \
    --theme-author-uri="https://pollora.dev" \
    --theme-uri="https://pollora.dev" \
    --theme-description="Pollora starter theme" \
    --theme-version="1.0.0"
```

2. **Develop** in `themes/pollora-starter/` — modify views, CSS, config, etc.

3. **Package** your changes back into this repository:

```bash
cd /path/to/theme-default
./bin/package-theme.sh /path/to/your-project/themes/pollora-starter
```

The script replaces all `pollora-starter` / `PolloraStarter` / `Theme\PolloraStarter` references with the appropriate `%placeholder%` tokens.

4. **Review, commit, tag and push**:

```bash
git diff
git add -A && git commit -m "feat: description of changes"
git tag x.y.z
git push origin main --tags
```

5. **Verify** by regenerating the theme from the updated tag:

```bash
rm -rf themes/pollora-starter
php artisan pollora:make-theme pollora-starter ...
```

### Placeholders

The following placeholders are replaced by `pollora:make-theme`:

| Placeholder | Replaced with |
|---|---|
| `pollora-starter` | Theme slug (e.g. `my-theme`) |
| `Theme\PolloraStarter` | PSR-4 namespace (e.g. `Theme\MyTheme`) |
| `https://pollora.dev` | Theme URL |
| `Pollora` | Author name |
| `https://pollora.dev` | Author URL |
| `Pollora starter theme` | Theme description |
| `1.0.0` | Version number |

### Important

- Always use `pollora-starter` as the dev theme name — the packaging script depends on it
- Never commit files with concrete theme names (check with `grep -r "pollora-starter" --include="*.php" --include="*.css"` before pushing)
- The `bin/` directory is excluded when the theme is downloaded by `pollora:make-theme`