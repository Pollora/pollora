# Plan : `pollora:make-block` — Intégration Gutenberg via Vite

**Objectif** : Commande Artisan qui scaffolde un bloc Gutenberg prêt à l'emploi dans un thème ou plugin Pollora, en s'appuyant sur Vite (pas de webpack/wp-scripts).

**Branche** : `feature/make-block` depuis `release/v13.4.0`

---

## 0. Contexte framework (acquis par exploration)

### Système d'assets existant (`src/Asset/`)

L'architecture Asset utilise un pattern **Container** pour isoler les builds par thème/plugin :

- **`AssetContainer`** (`Infrastructure/Repositories/`) — Stocke la config par container : `hotFile`, `buildDirectory`, `manifestPath`, `basePath`
- **`ViteManager`** (`Infrastructure/Services/`) — Wrap `Illuminate\Foundation\Vite`, initialise `useHotFile()`, `useBuildDirectory()`, `useManifestFilename()` par container. Expose `getAssetUrls(entrypoints)` (lecture manifest → `['js' => [...], 'css' => [...]]`), `asset(path)`, `isRunningHot()`
- **`AssetEnqueuer`** (`Infrastructure/Services/`) — Builder fluent (`.handle()`, `.container()`, `.useVite()`, `.toFrontend()`, `.toBackend()`, `.toEditor()`). Enqueue via `wp_enqueue_script/style`. Détecte console/CLI pour skip gracieux
- **`ModuleAssetManager`** (`src/Modules/`) — Enregistre automatiquement les containers pour thèmes et plugins via `setupModuleAssets()`. Convention : container `'theme'` pour thèmes, `'plugin.{slug}'` pour plugins

**Convention de nommage des containers** (dans `ModuleAssetManager::getContainerName()`) :
```php
'theme' => 'theme',              // Un seul thème actif
'plugin' => 'plugin.{slug}',     // Par plugin
```

**Config asset automatique** (dans `ModuleAssetManager::getAssetConfiguration()`) :
```php
[
    'hot_file' => public_path('{moduleName}.hot'),
    'build_directory' => 'build/{moduleType}/{moduleName}',
    'manifest_path' => 'manifest.json',
    'base_path' => 'resources/assets/',
]
```

### Structure thème réelle (`pollora-starter`)

```
themes/pollora-starter/
├── app/Providers/
│   ├── AssetServiceProvider.php    ← Asset::add('pollora-starter/script', 'app.js')->container('theme')->toFrontend()->useVite()
│   └── MenuServiceProvider.php
├── config/
│   ├── config.php                  ← theme name, assets path
│   ├── gutenberg.php               ← block categories + pattern categories
│   ├── providers.php               ← [] (providers auto-découverts via spatie)
│   └── ...
├── resources/
│   ├── assets/app.js               ← entry point Vite actuel
│   └── views/patterns/             ← block patterns Blade
├── vite.config.js                  ← laravel-vite-plugin, tailwindcss, blade HMR
├── package.json                    ← vite 8, tailwindcss 4, laravel-vite-plugin 3
├── theme.json                      ← WP theme.json v2 (couleurs, typo, spacing)
└── style.css                       ← WordPress theme headers
```

**vite.config.js actuel** — Points clés :
- `base: "/build/theme/" + themeName`
- `input: ["./resources/assets/app.js"]`
- `hotFile: path.join(publicDirectory, '${themeName}.hot')`
- `buildDirectory: path.join("build", "theme", themeName)`
- Détection Docker/DDEV, HTTPS automatique
- Blade HMR plugin custom
- `emptyOutDir: false` (important pour builds coexistants)

### Bloc existant dans le framework

- **`BlockRegistryInterface`** — Contrat pour `register_block_type()`, `registerBlockTypesFromMetadataCollection()`, `registerBlockCollectionFromManifest()`
- **`WpBlockRegistry`** — Implémentation wrappant les fonctions WP natives
- **`BlockPatternServiceProvider`** — Découvre les patterns Blade dans `resources/views/patterns/`, extrait les headers, enregistre via `register_block_pattern()`
- **`BlockCategoryServiceProvider`** — Lit `config/gutenberg.php`, enregistre les catégories via `block_categories_all` filter

### Discovery et providers thème

Les service providers d'un thème sont auto-découverts par `spatie/php-structure-discoverer` (pas besoin de les lister dans `config/providers.php`). Un nouveau `BlocksServiceProvider` dans `app/Providers/` sera automatiquement chargé.

### Commande generators existantes

- **`AbstractGeneratorCommand`** — Base avec traits `HasThemeSupport`, `HasPluginSupport`, `HasModuleSupport`, `ResolvesLocation`
- **`MakeThemeCommand`** — Pattern de référence : download GitHub, placeholder replacements, `npm install && npm run build`
- **`PostTypeMakeCommand`** — Génération simple depuis stub avec résolution de location

---

## 1. Signature de la commande

```bash
php artisan pollora:make-block <name> [options]
```

### Arguments

| Nom | Type | Obligatoire | Description |
|---|---|---|---|
| `name` | string | Oui | Slug en kebab-case (ex: `hero-banner`). Validé `/^[a-z][a-z0-9-]*$/` |

### Options

| Option | Défaut | Description |
|---|---|---|
| `--theme[=<slug>]` | thème actif | Cible thème. Sans valeur = thème actif |
| `--plugin=<slug>` | — | Cible plugin/module nwidart |
| `--namespace=<ns>` | slug de la cible | Namespace WP du bloc (avant le `/`) |
| `--title=<string>` | title-case du slug | Titre dans l'inserter |
| `--category=<cat>` | `widgets` | Catégorie Gutenberg |
| `--icon=<dashicon>` | `block-default` | Icône Dashicon |
| `--dynamic` | false | Bloc dynamique avec `render.php` au lieu de `save.jsx` |
| `--inner-blocks` | false | Support InnerBlocks |
| `--no-view-script` | false | Pas de `view.js` frontend |
| `--force` | false | Écrase sans confirmation |

### Résolution de cible

1. `--plugin` fourni → cible plugin (ignorer `--theme`)
2. `--theme=<slug>` → thème spécifié
3. `--theme` sans valeur → thème actif (via `get_option('stylesheet')`)
4. Aucune option → thème actif
5. `--theme` ET `--plugin` → erreur

---

## 2. Architecture des fichiers à créer dans le framework

```
src/Block/
├── Domain/
│   └── Contracts/
│       └── BlockRegistrarInterface.php
├── Infrastructure/
│   ├── Providers/
│   │   └── BlockServiceProvider.php
│   └── Services/
│       └── BlockRegistrar.php          ← Scanne un dossier, résout assets, enregistre les blocs
├── UI/
│   └── Console/
│       └── MakeBlockCommand.php        ← La commande artisan
└── stubs/                              ← Stubs publiables
    ├── block.json.stub
    ├── index.jsx.stub
    ├── edit.jsx.stub
    ├── save.jsx.stub
    ├── render.php.stub
    ├── editor.css.stub
    ├── style.css.stub
    ├── view.js.stub
    ├── BlocksServiceProvider.php.stub
    └── vite.config.blocks.stub         ← Fragment à injecter
```

---

## 3. `BlockRegistrar` — Service central

### Responsabilités

1. Scanner un dossier pour trouver les sous-dossiers contenant `block.json`
2. Pour chaque bloc, résoudre les chemins `file:./xxx.jsx` via le `ViteManager` du container correspondant
3. Enregistrer via `register_block_type()` avec les métadonnées résolues
4. Gérer `render.php` pour les blocs dynamiques
5. Lire `editor.deps.json` (généré par `@roots/vite-plugin`) pour les dépendances WordPress

### Intégration avec `src/Asset/`

```php
namespace Pollora\Block\Infrastructure\Services;

use Pollora\Asset\Application\Services\AssetManager;
use Pollora\Asset\Infrastructure\Services\ViteManager;
use Pollora\Asset\Infrastructure\Repositories\AssetContainer;

class BlockRegistrar implements BlockRegistrarInterface
{
    public function __construct(
        private readonly AssetManager $assetManager,
    ) {}

    public function registerDirectory(string $directory, string $containerName): void
    {
        // Scanner les sous-dossiers contenant block.json
        // Pour chaque bloc, appeler registerBlock()
    }

    public function registerBlock(string $blockDir, string $containerName): void
    {
        // 1. Lire block.json
        // 2. Résoudre le container via $this->assetManager->getContainer($containerName)
        // 3. Créer un ViteManager avec ce container
        // 4. Pour chaque champ file:./xxx :
        //    - Construire l'entry point relatif (resources/blocks/{slug}/xxx)
        //    - Résoudre l'URL via ViteManager::asset() (dev) ou getAssetUrls() (prod)
        //    - Enregistrer le handle WP via wp_register_script/style
        // 5. Lire editor.deps.json si présent (via manifest Vite)
        // 6. register_block_type() avec métadonnées résolues
    }
}
```

### Résolution des assets — Algorithme détaillé

Le `ViteManager` existant fournit exactement ce dont on a besoin :

```php
$container = $this->assetManager->getContainer($containerName);  // 'theme' ou 'plugin.{slug}'
$vite = new ViteManager($container);

if ($vite->isRunningHot()) {
    // Dev : URL directe vers le dev server
    $scriptUrl = $vite->asset("resources/blocks/{$slug}/index.jsx");
} else {
    // Prod : lecture du manifest, obtention des URLs hashées
    $urls = $vite->getAssetUrls(["resources/blocks/{$slug}/index.jsx"]);
    // $urls = ['js' => ['assets/blocks/hero-HASH.js'], 'css' => ['assets/blocks/hero-HASH.css']]
}
```

### Lecture de `editor.deps.json`

Fichier généré par `@roots/vite-plugin` dans le build directory. Contient les handles WP nécessaires :
```json
["wp-blocks", "wp-element", "wp-i18n", "wp-block-editor"]
```

Localisation via le manifest Vite :
```php
$manifest = json_decode(file_get_contents($container->getBuildDirectory() . '/manifest.json'), true);
// Chercher l'entrée editor.deps.json dans le manifest
// Sinon fallback sur un chemin direct
```

---

## 4. `MakeBlockCommand` — Flux d'exécution

### Étape 1 — Validation et résolution

- Valider le nom (regex)
- Résoudre la cible (thème/plugin) via les traits `HasThemeSupport`/`HasPluginSupport`
- Vérifier que le bloc n'existe pas déjà (sauf `--force`)
- Détecter si c'est le premier bloc (`resources/blocks/` n'existe pas ou est vide)

### Étape 2 — Bootstrap infrastructure (si premier bloc)

#### 2.1 Patcher `vite.config.js`

Ajouter au fichier existant :
- Import de `@roots/vite-plugin` et `glob`
- Découverte auto des blocs via `globSync`
- `wordpressPlugin()` dans les plugins Vite
- Les entry points blocs fusionnés dans `input`
- `resources/blocks/**` dans `refresh`

**Stratégie** : Rechercher des marqueurs dans le fichier existant. Si le fichier correspond au pattern standard Pollora (présence de `getThemeConfig`), injecter les modifications. Sinon, afficher les lignes à ajouter manuellement et continuer.

Modifications concrètes sur le `vite.config.js` du thème starter :

```javascript
// Ajouts en haut du fichier :
import { wordpressPlugin } from '@roots/vite-plugin';
import { globSync } from 'glob';
import path from 'path';  // déjà présent

// Ajout après les imports :
const blockEntries = globSync('./resources/blocks/*/index.{js,jsx,ts,tsx}')
    .reduce((acc, file) => {
        const slug = path.basename(path.dirname(file));
        acc[`blocks/${slug}`] = file;
        return acc;
    }, {});
const hasBlocks = Object.keys(blockEntries).length > 0;

// Modification de getThemeConfig() — fusionner blockEntries dans input :
input: ["./resources/assets/app.js", ...Object.values(blockEntries)],

// Modification de refresh :
refresh: [...refreshPaths, 'themes/'+themeName+'/resources/views/**', 'resources/blocks/**'],

// Ajout dans plugins[] :
...(hasBlocks ? [wordpressPlugin()] : []),
```

#### 2.2 Ajouter les dépendances npm

Vérifier `package.json` et ajouter dans `devDependencies` si absent :
```json
{
    "@roots/vite-plugin": "^2.0.0",
    "glob": "^11.0.0",
    "@wordpress/blocks": "^14.0.0",
    "@wordpress/block-editor": "^14.0.0",
    "@wordpress/components": "^29.0.0",
    "@wordpress/element": "^6.0.0",
    "@wordpress/i18n": "^5.0.0"
}
```

**Ne pas lancer `npm install`** — afficher les instructions.

#### 2.3 Créer `BlocksServiceProvider`

Pour un thème (`app/Providers/BlocksServiceProvider.php`) :
```php
namespace Theme\{ThemeNamespace}\Providers;

use Illuminate\Support\ServiceProvider;
use Pollora\Block\Infrastructure\Services\BlockRegistrar;

class BlocksServiceProvider extends ServiceProvider
{
    public function boot(BlockRegistrar $registrar): void
    {
        add_action('init', fn () => $registrar->registerDirectory(
            directory: dirname(__DIR__, 2) . '/resources/blocks',
            containerName: 'theme',
        ));
    }
}
```

**Pas besoin d'enregistrer le provider** — il est auto-découvert par spatie/php-structure-discoverer.

Pour un plugin, le namespace et chemin relatif changent mais la logique est identique. Le `containerName` sera `'plugin.{slug}'`.

### Étape 3 — Scaffolding du bloc

Créer `resources/blocks/{blockSlug}/` avec les fichiers depuis les stubs.

### Étape 4 — Retour console

```
✓ Block [acme/hero] created in themes/pollora-starter/resources/blocks/hero/
✓ BlocksServiceProvider created                         (si premier bloc)
✓ vite.config.js updated                                (si premier bloc)
✓ package.json updated                                  (si premier bloc)

Next steps:
  1. Run: cd themes/pollora-starter && npm install
  2. Run: npm run dev (for HMR) or npm run build
  3. Your block will appear in the editor under "Widgets" category
```

---

## 5. Stubs

Stockés dans `src/Block/stubs/`, publiables via `vendor:publish --tag=pollora-block-stubs`.

Placeholders : `{{ blockSlug }}`, `{{ blockNamespace }}`, `{{ blockFullName }}`, `{{ title }}`, `{{ category }}`, `{{ icon }}`, `{{ className }}` (PascalCase), `{{ targetSlug }}`.

### `block.json.stub`

```json
{
    "$schema": "https://schemas.wp.org/trunk/block.json",
    "apiVersion": 3,
    "name": "{{ blockFullName }}",
    "version": "1.0.0",
    "title": "{{ title }}",
    "category": "{{ category }}",
    "icon": "{{ icon }}",
    "description": "A {{ title }} block.",
    "keywords": ["{{ blockSlug }}"],
    "textdomain": "{{ targetSlug }}",
    "supports": { "html": false, "align": ["wide", "full"] },
    "editorScript": "file:./index.jsx",
    "editorStyle": "file:./editor.css",
    "style": "file:./style.css"
}
```

Ajouts conditionnels : `"render": "file:./render.php"` si `--dynamic`, `"viewScript": "file:./view.js"` si viewScript.

> Les chemins `file:./` sont des références logiques. Le `BlockRegistrar` les convertit en URLs Vite réelles via le `ViteManager`.

### Autres stubs

`index.jsx.stub`, `edit.jsx.stub`, `save.jsx.stub`, `render.php.stub`, `editor.css.stub`, `style.css.stub`, `view.js.stub` — contenu identique à la spec originale (section 3 du plan initial).

---

## 6. `BlockServiceProvider` (framework)

Enregistrement dans `PolloraServiceProvider` :

```php
$this->app->register(BlockServiceProvider::class);
```

Le provider enregistre :
- `BlockRegistrar` comme singleton
- La commande `MakeBlockCommand` (`runningInConsole`)
- Publie les stubs (`vendor:publish --tag=pollora-block-stubs`)

---

## 7. Cas d'erreur

| Situation | Comportement |
|---|---|
| `name` invalide (regex) | Erreur + exemple valide |
| Thème/plugin introuvable | Erreur + liste des cibles disponibles |
| Bloc existe déjà sans `--force` | Demander confirmation interactive |
| `vite.config.js` trop divergent | Afficher lignes à ajouter manuellement + continuer |
| `package.json` absent | Erreur : « Lance d'abord `npm init -y` » |
| `--theme` ET `--plugin` | Erreur : options mutuellement exclusives |

---

## 8. Tests

### Tests unitaires (Pest)

- `BlockRegistrarTest::scansDirectoryAndRegistersBlocks`
- `BlockRegistrarTest::resolvesFilePrefixedPathsViaViteManager`
- `BlockRegistrarTest::readsEditorDepsJsonWhenPresent`
- `BlockRegistrarTest::fallsBackGracefullyWhenEditorDepsJsonMissing`
- `BlockRegistrarTest::handlesDynamicBlocksWithRenderPhp`

### Tests de commande

- `MakeBlockCommandTest::createsBlockInActiveTheme`
- `MakeBlockCommandTest::createsBlockInSpecificTheme`
- `MakeBlockCommandTest::createsBlockInPlugin`
- `MakeBlockCommandTest::bootstrapsInfrastructureOnFirstBlock`
- `MakeBlockCommandTest::doesNotBootstrapOnSubsequentBlocks`
- `MakeBlockCommandTest::failsOnInvalidName`
- `MakeBlockCommandTest::failsWhenBothThemeAndPluginProvided`
- `MakeBlockCommandTest::generatesDynamicBlockWithRenderPhp`
- `MakeBlockCommandTest::respectsForceFlag`
- `MakeBlockCommandTest::patchesViteConfigCorrectly`
- `MakeBlockCommandTest::addsNpmDependencies`

### Tests d'intégration E2E (via MCP Chrome)

Après implémentation, vérifier dans le navigateur :
1. Scaffolder un bloc `hero` dans le thème actif
2. `npm install && npm run build` dans le thème
3. Naviguer vers l'éditeur Gutenberg dans Chrome
4. Vérifier que le bloc apparaît dans l'inserter
5. Insérer le bloc, vérifier le rendu éditeur
6. Publier et vérifier le rendu frontend
7. Tester le mode HMR (`npm run dev`) — vérifier le hot reload

---

## 9. Documentation

### `documentation/blocks.md`

- Comment créer un bloc (commande + exemples)
- Structure des fichiers générés
- Personnalisation des stubs via `vendor:publish`
- Fonctionnement du pont Vite/WordPress (`editor.deps.json`, manifest, dev vs prod)
- Blocs dynamiques avec `render.php`
- InnerBlocks
- Debugging

---

## 10. Découpage en PRs

1. **PR 1 — `BlockRegistrar` + `BlockServiceProvider`** : service central, intégration avec `src/Asset/`, tests unitaires
2. **PR 2 — `MakeBlockCommand` + stubs** : commande, stubs, tests de commande (sans bootstrap Vite)
3. **PR 3 — Bootstrap Vite** : patch `vite.config.js`, `package.json`, création du provider thème
4. **PR 4 — Documentation + exemple** : `blocks.md`, bloc exemple dans le skeleton, tests E2E Chrome

---

## 11. Hors-scope

- Blocs ACF (`--template=acf` → futur)
- `viewScriptModule` / Interactivity API → v2
- Support webpack/wp-scripts → refusé (Vite uniquement)
- `blocks-manifest.php` WP 6.7+ → optimisation future
- HMR JavaScript dans l'éditeur Gutenberg → limitation `@roots/vite-plugin`, acceptable

---

## 12. Glossaire

| Terme | Définition |
|---|---|
| **Cible** | Thème ou plugin dans lequel le bloc est scaffoldé |
| **Bootstrap** | Mise en place de l'infra (vite.config, package.json, provider) au premier bloc |
| **BlockRegistrar** | Service PHP qui scanne et enregistre les blocs auprès de WordPress |
| **AssetContainer** | Config Vite par cible (`hotFile`, `buildDirectory`, `manifestPath`) — dans `src/Asset/` |
| **ViteManager** | Résolution d'URLs dev/prod via Laravel Vite — dans `src/Asset/` |
| **AssetEnqueuer** | Builder fluent pour wp_enqueue_script/style — dans `src/Asset/` |
| **editor.deps.json** | Manifest de `@roots/vite-plugin` listant les handles WP nécessaires |
| **ModuleAssetManager** | Service qui enregistre automatiquement les containers asset par thème/plugin |