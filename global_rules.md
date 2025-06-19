# ✅ **Modern PHP Project — Guidelines**

## 1. ✨ Coding Standards

- Use **PHP 8.3+** and leverage its latest features (readonly properties, enums, typed constants, etc.).
- Follow code style conventions defined in `pint.json` or equivalent (`PHP-CS-Fixer`).
- Enable **strict types** (`declare(strict_types=1);`) in all PHP files.
- Define **explicit types for arrays** and enforce them using **PHPStan** (`array shapes` or dedicated DTOs).
- No business logic should live in controllers or route files — **respect separation of concerns**.
- Avoid using static facades like `DB::`; prefer clean service injection or `Model::query()` style logic.

## 2. 🧱 Architecture

### 2.1 General Structure
- Create folders only when needed
- Remove `.gitkeep` once files are added
- Use consistent naming conventions
- Make services testable and loosely coupled
- Follow **SOLID**, **DRY**, **YAGNI**

### 2.2 Directory Conventions

| Directory                        | Purpose                                                   |
|----------------------------------|-----------------------------------------------------------|
| `src/Http/Controllers`           | Controllers, lightweight, no business logic               |
| `src/Http/Requests`             | Request validation objects                                |
| `src/Actions`                   | Domain-specific use cases, named using `CreateX`, `UpdateX`, etc. |
| `src/Models`                    | Business models or Eloquent models if using Laravel (no `fillable`) |
| `src/DTO/`                      | Strongly typed data transfer objects                      |
| `src/Services/`                 | Application-level services or third-party integrations    |
| `database/migrations`           | Schema migrations, omit `down()` method entirely          |

> 💡 If using Laravel: do not use abstract `Controller.php` base classes. Prefer clarity and modularity.

---

## 3. 🧪 Testing
- Use **Pest PHP**
- Cover business logic with tests
- Organize by functionality

### 3.1 Test Directory Structure

| Test Folder                         | Purpose                                |
|-------------------------------------|----------------------------------------|
| `tests/Feature/Http`                | Feature tests for controllers          |
| `tests/Feature/Console`             | CLI command tests                      |
| `tests/Unit/Actions`                | Unit tests for business actions        |
| `tests/Unit/Models`                 | Unit tests for models                  |
| `tests/Unit/Services`               | Unit tests for service classes         |
| `tests/Unit/DTO`                    | Unit tests for data structures         |

> ✔️ Run `composer lint` after editing or creating files  
> ✔️ Run `composer test` before submitting any code  
> ❌ Do not delete any test without explicit approval

---

## 4. 🎨 UI & Frontend (if applicable)

- Use **TailwindCSS** for all styles.
- Keep all user interfaces **clean, minimal, and purpose-driven**.
- Avoid legacy frontend libraries (e.g., jQuery, Bootstrap).
- For interactivity, use **AlpineJS** or **React**, depending on the stack.

---

## 5. 🚀 Task Completion Requirements

- When making frontend-related changes, **rebuild assets** (`npm run build`, `vite build`, etc.).
- Do not add, remove, or update dependencies in `composer.json` or `package.json` without prior approval.
- Ensure the following before marking a task as complete:
    - ✅ Code follows all defined conventions
    - ✅ All tests pass (`composer test`)
    - ✅ Code is clean per linters (`composer lint`)
    - ✅ No errors or warnings in static analysis (PHPStan)
    - ✅ Assets are compiled

---

## 6. 🎉 Git commits

Commits must follow the Conventional Commits specification. It's a lightweight convention on top of commit messages.
It provides an easy set of rules for creating an explicit commit history which makes it easier to write automated tools on top of.
This convention dovetails with [SemVer](http://semver.org), by describing the features, fixes, and breaking changes made in commit messages.

The commit message should be structured as follows:

```
<type>[optional scope]: <description>

[optional body]

[optional footer(s)]
```
---

The commit contains the following structural elements, to communicate intent to the
consumers of your library:

1. **fix:** a commit of the _type_ `fix` patches a bug in your codebase (this correlates with [`PATCH`](http://semver.org/#summary) in Semantic Versioning).
1. **feat:** a commit of the _type_ `feat` introduces a new feature to the codebase (this correlates with [`MINOR`](http://semver.org/#summary) in Semantic Versioning).
1. **BREAKING CHANGE:** a commit that has a footer `BREAKING CHANGE:`, or appends a `!` after the type/scope, introduces a breaking API change (correlating with [`MAJOR`](http://semver.org/#summary) in Semantic Versioning).
   A BREAKING CHANGE can be part of commits of any _type_.
1. _types_ other than `fix:` and `feat:` are allowed, for example [@commitlint/config-conventional](https://github.com/conventional-changelog/commitlint/tree/master/%40commitlint/config-conventional) (based on the [Angular convention](https://github.com/angular/angular/blob/22b96b9/CONTRIBUTING.md#-commit-message-guidelines)) recommends `build:`, `chore:`,
   `ci:`, `docs:`, `style:`, `refactor:`, `perf:`, `test:`, and others.
1. _footers_ other than `BREAKING CHANGE: <description>` may be provided and follow a convention similar to
   [git trailer format](https://git-scm.com/docs/git-interpret-trailers).

## 💡 Recommendations
- Use **service containers** for DI
- Prefer **Value Objects/DTOs** over arrays
- Use **PHPStan/Psalm** for static analysis
- Use **Rector** for modernization
- Add **PHPDoc** for IDE support
