# Plan : Fiabiliser le CI/CD et la qualité du framework Pollora

## Contexte

Le CI du framework est cassé : `symfony/var-dumper ^8.0` requiert PHP 8.4+ mais le CI tourne sur PHP 8.3. Tous les runs échouent au `composer install`. Au-delà de ce fix immédiat, le workflow de qualité doit être renforcé pour un framework en production.

## Phase 1 — Fix immédiat (débloquer le CI)

### 1.1 Mettre à jour la contrainte PHP
- **`composer.json`** : `"php": "^8.3"` → `"php": "^8.4"` (Laravel 13 + Symfony 8 requièrent PHP 8.4+)
- Mettre aussi à jour le skeleton `pollora/pollora` pour aligner

### 1.2 Mettre à jour les workflows
- **`.github/workflows/tests.yml`** : PHP `8.3` → `8.4` (matrix + code-quality job)
- **`.github/workflows/coverage.yml`** : PHP `8.3` → `8.4`
- **`.github/workflows/deploy.yml`** : PHP `8.3` → `8.4`

### Vérification
```bash
# Localement
cd ~/Sites/pollora-packages/framework
composer install
composer test
# Push sur develop → CI doit passer au vert
```

## Phase 2 — Consolider les workflows CI

### 2.1 Fusionner tests.yml + coverage.yml → ci.yml
Un seul workflow `ci.yml` avec 3 jobs :
- **tests** : Pest avec coverage (une seule exécution au lieu de deux)
- **code-quality** : Pint + PHPStan + Rector
- **coverage-upload** : Envoi du rapport à Codecov (dépend de tests)

### 2.2 Ajouter le cache Composer
Aucun workflow ne cache les dépendances. Ajouter `actions/cache@v4` pour `composer config cache-files-dir` → divise le temps d'install par 2-3x.

### 2.3 Ajouter `composer audit`
Étape dans le job code-quality pour détecter les vulnérabilités connues.

### 2.4 Mettre à jour les triggers pour le Gitflow complet
```yaml
on:
  push:
    branches: [main, develop, 'feature/**', 'hotfix/**', 'release/**']
  pull_request:
    branches: [main, develop]
```

### Fichiers
- Créer `.github/workflows/ci.yml`
- Supprimer `.github/workflows/tests.yml`
- Supprimer `.github/workflows/coverage.yml`

## Phase 3 — Automatisation des releases

### 3.1 Améliorer deploy.yml
- Extraire la section de version du CHANGELOG.md pour l'utiliser comme body de la release GitHub (au lieu des auto-generated notes)

### 3.2 Validation du changelog sur les PRs vers main
- Job conditionnel : vérifier que la section `[Unreleased]` du CHANGELOG.md contient des entrées quand un PR cible `main`

### Fichiers
- Modifier `.github/workflows/deploy.yml`
- Ajouter un job `validate-changelog` dans `ci.yml`

## Phase 4 — Scan de sécurité planifié

### 4.1 Workflow security.yml
- Exécution hebdomadaire (cron lundi 6h)
- `composer audit` sur les dépendances
- Se déclenche aussi sur push develop/main

### Fichiers
- Créer `.github/workflows/security.yml`

## Phase 5 — Protection des branches (GitHub Settings)

### Configuration manuelle dans les settings du repo
- **`develop`** : require status checks (tests, code-quality), require branch up-to-date, no force push
- **`main`** : idem + require 1 approval, restrict merge sources (develop, hotfix/*)

## Phase 6 — Améliorations des tests (futur)

Non implémentées dans cette itération, mais planifiées :
- `FeatureTestCase` basé sur Orchestra Testbench pour les tests Feature
- Réduire la fragilité de `helpers.php` (1211 lignes de mocks WordPress)
- Type coverage dans le CI (`pest --type-coverage --min=100`)
- Suite d'intégration avec environnement WordPress réel (long terme)

## Résumé des quality gates

| Check | Outil | Bloque le merge |
|---|---|---|
| Tests unitaires | Pest `--min=100` | Oui |
| Code style | Pint `--test` | Oui |
| Analyse statique | PHPStan level 5 | Oui |
| Rector dry-run | Rector `--dry-run` | Oui |
| Audit sécurité | `composer audit` | Oui |
| Titre PR | semantic-pull-request | Oui |
| Changelog | Custom (PRs → main) | Oui |
| Coverage report | Codecov | Non (informatif) |

## Ordre d'exécution

1. Phase 1 (fix PHP) → commit + push → CI vert
2. Phase 2 (ci.yml consolidé) → commit + push
3. Phase 3 (deploy amélioré) → commit + push
4. Phase 4 (security.yml) → commit + push
5. Phase 5 (branch protection) → config GitHub manuelle
