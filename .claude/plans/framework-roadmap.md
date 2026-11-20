# Plan : Roadmap d'évolution du framework Pollora

## Contexte

Le framework Pollora v13.x est stable avec un CI complet (Pest 100% coverage, PHPStan level 5, Rector, Pint), une architecture DDD, un système de discovery par attributs, et des intégrations WordPress (hooks, post types, taxonomies, REST API, auth). Après avoir consolidé le CI, migré les tests vers Pest, et ajouté le version check (#162), il est temps de planifier les prochaines évolutions.

Ce plan couvre les évolutions court, moyen et long terme, organisées en phases priorisées.

---

## Phase 1 — Fiabilisation des tests (v13.4) ✅

> **Statut** : Terminé — mergé dans `release/v13.4.0`
> **Branches** : `feature/brain-monkey-migration`, `feature/testbench-feature-tests`, `feature/type-coverage-ci`

### 1.1 ✅ Remplacer les mocks WordPress manuels par Brain Monkey
- `brain/monkey` ajouté en require-dev
- `tests/Unit/helpers.php` réduit de 1302 → ~450 lignes
- 12 fichiers de test migrés vers `Brain\Monkey\Functions\when()`/`stubs()`
- Patchwork chargé dans `bootstrap.php` pour interception de fonctions
- `PassthroughTranslator` pour gérer `__()` Laravel vs WordPress en tests

### 1.2 ✅ Exploiter Orchestra Testbench pour les tests Feature
- 18 nouveaux tests Testbench pour `DiscoveryServiceProvider`, `HookServiceProvider`, `VersionCheckServiceProvider`
- Brain Monkey intégré dans Pest `beforeEach`/`afterEach` pour Feature tests Testbench

### 1.3 ✅ Ajouter le type coverage dans le CI
- Script `test:type-coverage` avec `--min=98` (coverage actuel : 98.2%)
- Intégré dans le job `code-quality` du CI

---

## Phase 2 — Internationalisation du framework (v13.4) ✅

> **Statut** : Terminé — mergé dans `release/v13.4.0`
> **Branche** : `feature/i18n-text-domain-v2`

### 2.1 ✅ Text domain `pollora` pour le framework
- Labels auto-générés utilisent `sprintf(__('Edit %s', 'pollora'), $singular)` — extractibles
- AdminNotice et SiteHealthCheck internationalisés avec text domain `'pollora'` littéral
- `PassthroughTranslator` ajouté aux tests pour gérer les deux signatures de `__()`

### 2.2 ✅ Paramètre `textDomain` sur les attributs
- `#[PostType(textDomain: 'my-plugin')]` et `#[Taxonomy(textDomain: 'my-theme')]`
- Propagé dans les Discovery → `generateLabels()` utilise le text domain fourni
- Fallback sur `'pollora'` si non renseigné
- Placeholder `'textdomain'` supprimé de tout le code source

### 2.3 ✅ `#[Labels]` avec paramètres nommés
- Réécriture de `#[Labels]` PostType et Taxonomy avec paramètres nommés (`allItems:`, `editItem:`, etc.)
- **Merge partiel** : seuls les labels fournis sont overridés, les autres gardent leurs valeurs auto-générées
- 10 tests unitaires (merge, paramètres nommés, textDomain)
- Limitation documentée : `__()` impossible dans les attributs PHP (expressions constantes)

### 2.4 ✅ Documentation i18n
- Section "Internationalization" ajoutée dans `post-types.md` et `taxonomies.md`
- Tableau comparatif des 3 approches : `#[Labels]` (statique), `withArgs()` (traduisible), `configuring()` (dynamique + traduisible)
- Exemples dans le skeleton (`test-setup`) : `Project.php` (#[Labels]), `Service.php` (withArgs), `ProjectCategory.php` (#[Labels])

### Reste à faire (non bloquant, peut être traité en Phase 2 bis)
- [ ] Générer le fichier `languages/pollora.pot` (via `wp i18n make-pot` sur le framework)
- [ ] Ajouter `load_textdomain('pollora', ...)` dans `PolloraServiceProvider::boot()` pour charger les traductions
- [ ] Envisager une mise à jour du package `pollora/entity` pour supporter `textDomain`

---

## Phase 3 — Dashboard admin Pollora (v13.4) ✅

> **Statut** : Terminé — mergé dans `release/v13.4.0`
> **Branche** : `feature/dashboard`

### 3.1 ✅ Page d'information framework
- Page admin sous **Outils > Pollora** avec logo SVG inline et branding gradient Pollora
- Cards affichées : Environment, WordPress Config (WP_DEBUG, multisite, permalinks), Post Types (avec labels/slugs), Taxonomies (avec labels/slugs), Hooks (actions/filters), REST API Routes, WP-CLI Commands, Scheduled Tasks, Auto-discovered Providers, Laravel Modules (enabled/disabled via nwidart), Discovery Cache, Discovery Performance (cache hits/misses, classes traitées), Active Theme
- Badge de notification sur le menu quand une mise à jour est disponible (pattern `update-plugins` natif WP)
- Détection des versions dev (`dev-develop`, `dev-main`) pour éviter les faux positifs

### 3.2 ✅ Commande `pollora:status`
- Sortie formatée avec version, environnement, discovery, modules, cache, thème
- Flag `--json` pour sortie machine (AI agents, CI pipelines)
- Détection des versions dev avec affichage adapté

### 3.3 ✅ Documentation
- `documentation/dashboard.md` : guide complet (dashboard, CLI, JSON, accès programmatique)
- Index de la documentation mis à jour avec section "Monitoring & Tooling"

### 3.4 ✅ Tests
- 17 tests unitaires pour `SystemInfoCollector` (toutes les méthodes collect*)
- 3 tests Feature Testbench pour `DashboardServiceProvider`
- PHPStan level 5 : 0 erreurs

---

## Phase 4 — Mise à jour assistée (v14.0)

### 4.1 Commande `pollora:update`
**Problème** : Aucun mécanisme de mise à jour assistée. Les développeurs doivent manuellement mettre à jour `composer.json` et gérer les breaking changes.

**Solution** :
- Commande interactive qui :
  1. Vérifie la version installée vs la dernière disponible
  2. Affiche le changelog des versions intermédiaires
  3. Met en surbrillance les breaking changes
  4. Met à jour la contrainte dans `composer.json`
  5. Lance `composer update pollora/framework`
  6. Exécute les migrations si nécessaire

**Fichiers clés** :
- `src/VersionCheck/` (réutiliser le checker existant)
- Nouveau : `src/Update/UI/Console/UpdateCommand.php`

---

## Phase 5 — Consolidation du système de plugins (v14.x)

### 5.1 ✅ Finaliser `pollora:make-plugin`

> **Statut** : Terminé — plugin-default v0.3 publié
> **Repo** : `Pollora/plugin-default` (tag `0.3`)

- Plugin de dev `pollora-demo-plugin` créé dans le skeleton test (même pattern que `pollora-starter` pour les thèmes)
- Script `bin/package-plugin.sh` ajouté : convertit le plugin de dev en template avec placeholders
- Template nettoyé : suppression `.idea/`, fix CSS (`body opacity`), admin-notice corrigée, WP 6.0+/PHP 8.2
- Placeholders : `%plugin_name%`, `%plugin_namespace%`, `%plugin_slug%`, `%plugin_function_name%`, `%PLUGIN_NAME%`, `%plugin_version%`, `%plugin_author%`, `%plugin_author_uri%`, `%plugin_uri%`, `%plugin_description%`
- Cycle complet testé : dev plugin → package → `pollora:make-plugin test-plugin` → plugin fonctionnel
- Structure générée : `app/` (Plugin class + Providers), `config/`, `resources/` (assets + views), `vite.config.js`, `package.json`

### 5.2 Plugin auto-discovery amélioré
**État actuel** : `PluginRepository` scanne les dossiers mais le bootstrapping des service providers n'est pas aussi automatique que pour les thèmes.

**Solution** :
- Aligner le plugin discovery sur le pattern `ThemeServiceProviderScout`
- Auto-découvrir les providers dans `Plugin\{Name}\Providers\`

---

## Phase 6 — Support multisite (v14.x / v15.0)

### 6.1 Audit et documentation multisite
**État actuel** : Support partiel — `WordPress::multisite()`, `Bootstrap::fixNetworkUrl()`, WordPress auth fonctionne nativement en multisite. Mais rien n'est documenté ni testé.

**Solution** :
- Auditer chaque module pour le comportement multisite
- Documenter les limitations connues
- Ajouter des tests Feature avec `is_multisite() → true`
- Points critiques : discovery (par site ou global ?), cache (par blog ?), routes (sous-domaines vs sous-dossiers)

### 6.2 Configuration multisite-aware
- `config/wordpress.php` : support des constantes multisite (`MULTISITE`, `SUBDOMAIN_INSTALL`, etc.)
- `Bootstrap.php` : détection automatique multisite
- Service providers : `boot()` conditionnel par blog si nécessaire

---

## Phase 7 — Tests d'intégration WordPress réel (v15.0)

### 7.1 Environnement CI avec WordPress
**Problème** : Tous les tests mockent WordPress. Aucun test ne vérifie le comportement réel.

**Solution** :
- Ajouter un job CI optionnel avec `wp-env` ou Docker (WordPress + MySQL)
- Installer Pollora dans un vrai environnement WordPress
- Tester : boot complet, enregistrement de routes `Route::wp()`, résolution de templates, post types enregistrés via `register_post_type()`
- Garder ce job comme "informatif" (non-bloquant) dans un premier temps

**Fichiers clés** :
- `.github/workflows/ci.yml` (nouveau job `integration`)
- `tests/Integration/` (nouveau dossier)

---

## Phase 8 — Simplification de l'API de registration (v13.4) ✅

> **Statut** : Terminé — mergé dans `release/v13.4.0`

### 8.1 ✅ Suppression de l'approche config-based
- Supprimé `config/posttypes.php`, `src/PostType/config/post-types.php`, `src/Taxonomy/config/taxonomies.php`
- Supprimé `registerConfiguredPostTypes()`, `registerTaxonomies()`, `registerConfiguredTaxonomies()`
- Nettoyé `ConfigServiceProvider` (ne publie plus que `wordpress.php`)
- Documentation mise à jour (sections config-based retirées de `post-types.md` et `taxonomies.md`)
- Issue [#272](https://github.com/Pollora/framework/issues/272) fermée
- Seules les approches conservées : attributs PHP (`#[PostType]`/`#[Taxonomy]`) + `withArgs()` + `configuring()`

---

## Résumé des priorités

| Phase | Version | Priorité | Effort | Statut |
|-------|---------|----------|--------|--------|
| 1. Tests (Brain Monkey, Testbench, type-coverage) | v13.4 | Haute | Moyen | ✅ Done |
| 2. Internationalisation | v13.4 | Moyenne | Faible | ✅ Done |
| 3. Dashboard admin | v13.4 | Moyenne | Moyen | ✅ Done |
| 4. Mise à jour assistée | v14.0 | Moyenne | Moyen | |
| 5. Plugins consolidation | v14.x | Moyenne | Moyen | 🔧 5.1 Done |
| 6. Multisite | v14.x/v15.0 | Basse | Élevé | |
| 7. Tests intégration WP réel | v15.0 | Basse | Élevé | |
| 8. Simplification registration API | v13.4 | Basse | Faible | ✅ Done |

---

## Quick wins ✅

1. ✅ **Renommer `src/Taxonomy/config/post-types.php`** → `taxonomies.php`
2. ✅ **Créer les issues GitHub** : #268 (Phase 4), #269 (Phase 5), #270 (Phase 6), #271 (Phase 7), #272 (Phase 8 — fermée)
3. ✅ **Générer le fichier `.pot`** + `load_textdomain` + 7 traductions (fr, es, de, pt-BR, it, nl, ja)