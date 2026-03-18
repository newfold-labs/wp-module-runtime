# Agent guidance – wp-module-runtime

This file gives AI agents a quick orientation to the repo. For full detail, see the **docs/** directory.

## What this project is

- **wp-module-runtime** – Runtime for Newfold WP modules and plugins. Exposes data to the admin app by building a runtime object (admin URL, REST URL/nonce, capabilities, site info, plugin/woo flags, etc.), passing it through the `newfold_runtime` filter, and outputting it as `window.NewfoldRuntime` on admin pages. Also provides a small JS helper (built from `src/index.js`). Maintained by Newfold Labs.

- **Stack:** PHP 7.4+ (see `composer.json` platform). Small JS bundle (webpack) for the runtime helper; no React UI.

- **Architecture:** Registers on `newfold_container_set`; creates a `Runtime` instance and calls `loadIntoPage( 'admin_enqueue_scripts' )`. On that action it registers an inline script that sets `window.NewfoldRuntime`. The container must provide `capabilities`; other modules (e.g. context) merge data via the `newfold_runtime` filter.

## Key paths

| Purpose | Location |
|---------|----------|
| Bootstrap | `bootstrap.php` – hooks `newfold_container_set`, creates Runtime, loads into page |
| Runtime builder | `includes/Runtime.php` – `prepareRuntime()`, `register_runtime()` |
| JS helper | `src/index.js` – wrapper for `window.NewfoldRuntime` (build output used by host) |
| Build | `scripts/webpack.config.js` |
| Tests | `tests/` (Codeception wpunit) |

## Essential commands

```bash
composer install
composer run lint
composer run fix
composer run test
composer run test-coverage
```

For the JS build (if needed by host): see `scripts/` and package.json scripts if present.

## Documentation

- **Full documentation** is in **docs/**. Start with **docs/index.md**.
- **CLAUDE.md** is a symlink to this file (AGENTS.md).

---

## Keeping documentation current

**When you change code, features, or workflows, update the docs so they stay accurate.** Keep **docs/index.md** current: when you add, remove, or rename doc files, update the table of contents (and quick links if present).

- Keep all docs current, not only the ones listed here.
- Prefer updating the appropriate file(s) in **docs/** over leaving docs out of date.
- When adding or changing REST routes or public API, update **api.md**. When adding or changing dependencies, update **dependencies.md**. When cutting a release, update **docs/changelog.md**.
