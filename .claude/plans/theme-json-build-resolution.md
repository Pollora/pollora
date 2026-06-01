# Plan : Résolution dynamique du theme.json buildé

## Contexte

Le plugin Vite `@roots/vite-plugin` (`wordpressThemeJson`) génère un `theme.json` enrichi dans `public/build/theme/{slug}/assets/theme.json` en extrayant les variables CSS (couleurs Tailwind, typographies, border-radius) du CSS compilé et en les fusionnant avec un `theme.json` de base.

### Problème actuel

Un hack `copy-theme-json` dans `vite.config.js` copie ce fichier buildé vers la racine du thème (`themes/{slug}/theme.json`) pour que WordPress le lise. C'est fragile car :
- Le fichier buildé écrase le fichier source lors du build
- Le `theme.json` source et buildé sont mélangés dans le même fichier
- En dev (HMR), le fichier n'est pas mis à jour dynamiquement

### Solution

Utiliser le filtre WordPress `wp_theme_json_data_theme` (WP 6.1+, `class-wp-theme-json-resolver.php:292`) pour injecter dynamiquement les données du `theme.json` buildé. Plus besoin de copier le fichier.

## Architecture

### 1. Domain Contract : `ThemeJsonResolverInterface`

**Fichier** : `src/Theme/Domain/Contracts/ThemeJsonResolverInterface.php`

```php
interface ThemeJsonResolverInterface
{
    public function resolve(string $themeSlug): ?array;
}
```

### 2. Infrastructure Service : `ThemeJsonResolver`

**Fichier** : `src/Theme/Infrastructure/Services/ThemeJsonResolver.php`

- Lit le `theme.json` buildé depuis `public/build/theme/{slug}/assets/theme.json`
- Utilise `public_path()` pour résoudre le chemin
- Cache le résultat en mémoire pour éviter les lectures FS multiples
- Retourne `null` si le fichier n'existe pas (fallback au comportement WordPress natif)

### 3. Hook WordPress : `wp_theme_json_data_theme`

**Intégration** : via `ThemeServiceProvider::boot()`

- Hook sur `wp_theme_json_data_theme` avec priorité normale
- Récupère le slug du thème actif
- Lit le `theme.json` buildé via `ThemeJsonResolver`
- Remplace les données du `WP_Theme_JSON_Data` par les données buildées
- Si pas de fichier buildé → laisse passer sans modification

### 4. Nettoyage

- Supprimer le plugin `copy-theme-json` de `themes/apiary/vite.config.js`
- Le `theme.json` source à la racine du thème reste inchangé (c'est l'input du build)

## Fichiers modifiés

| Fichier | Action |
|---------|--------|
| `framework/src/Theme/Domain/Contracts/ThemeJsonResolverInterface.php` | Créer |
| `framework/src/Theme/Infrastructure/Services/ThemeJsonResolver.php` | Créer |
| `framework/src/Theme/Infrastructure/Providers/ThemeServiceProvider.php` | Modifier (register + boot) |
| `themes/apiary/vite.config.js` | Modifier (supprimer copy-theme-json) |