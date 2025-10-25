# Repository Guidelines

## Project Structure & Module Organization
- `app/` holds controllers in `app/Http/Controllers` and Eloquent models in `app/Models`; keep feature logic close to these folders.
- Routes stay in `routes/web.php` (HTML) and `routes/api.php` (JSON). Group CRUD endpoints with `Route::resource('posts', PostController::class)`.
- Views and front-end code live under `resources/`; Vite writes compiled assets into `public/`.
- Schema, factories, and seeders reside in `database/`, while automated checks start from `tests/TestCase.php` and branch into `tests/Feature` or `tests/Unit`.

## Build, Test, and Development Commands
```bash
composer install          # resolves PHP dependencies
npm install               # installs Vite/Tailwind toolchain
php artisan serve         # launches the Laravel dev server
npm run dev               # runs Vite with hot module reload
npm run build             # produces the production asset bundle
composer dev              # spawns server, queue listener, and Vite together
```
Run `php artisan migrate --seed` whenever migrations change so the SQLite file stays current.

## Coding Style & Naming Conventions
- `.editorconfig` enforces UTF-8, LF endings, and four-space indentation across PHP, Blade, and TypeScript.
- Run Pint (`./vendor/bin/pint`) pre-commit; it applies PSR-12 with Laravel tweaks.
- Use StudlyCase for classes (`PostPolicy`), camelCase for methods, and resourceful route names like `posts.index` or `posts.store`.

## Testing Guidelines
- Pest is configured in `tests/Pest.php`; write scenarios with `it()` blocks and expressive expectation chains.
- Run `php artisan test` locally (or `composer test` for the config clear step) and keep feature specs in `tests/Feature/PostCrudTest.php` style files.
- Cover validation, authorization, and database state so CRUD regressions surface fast.

## Commit & Pull Request Guidelines
- Recent history shows terse summaries; switch to imperative, scoped messages such as `feat(posts): allow soft deletion`.
- Keep each commit focused and ship accompanying migrations or seeds in the same change set.
- PRs must outline the problem, summarize the fix, list verification (`php artisan test`, `npm run build`), and attach screenshots for UI updates.
- Reference linked issues and flag breaking changes or manual deployment steps.

## Environment & Configuration Tips
- Copy `.env.example` to `.env`, generate the key with `php artisan key:generate`, and keep credentials out of Git.
- Use `composer dev` for the full stack locally, `php artisan queue:listen` for jobs, and rebuild caches (`config:cache`, `route:cache`) only when debugging cache-sensitive issues.
