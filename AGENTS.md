# Repository Guidelines

## Project Structure & Module Organization
`app/` hosts domain logic (HTTP controllers, models, jobs) while `resources/views` and `resources/js` hold Blade templates and Vite-driven assets. Front-end entry points live under `resources/js/pages`, compiled through `vite.config.js` into `public/build`. Routing sits in `routes/web.php` for browser traffic and `routes/api.php` for JSON APIs. Database migrations, factories, and seeders are grouped under `database/` and should mirror the table or feature they support. Automated tests reside in `tests/Feature` for end-to-end HTTP flows and `tests/Unit` for isolated classes. Media uploads and logs stay in `storage/`, which is never committed.

## Build, Test, and Development Commands
- `composer install && npm install`: install PHP and Node dependencies.
- `cp .env.example .env && php artisan key:generate`: bootstrap environment secrets.
- `php artisan serve` or `composer dev`: run the Laravel HTTP server (the latter also runs queues, logs, and Vite).
- `npm run dev`: watch assets with hot reload; use `npm run build` for a production bundle.
- `php artisan test` or `composer test`: execute the PHPUnit suite after clearing cached config.

## Coding Style & Naming Conventions
Target PHP 8.2 with PSR-12 spacing (4-space indents, braces on new lines). Format PHP via `./vendor/bin/pint` before committing; JS/TS should follow the defaults enforced by Vite and Prettier configs, with camelCase variables and PascalCase components. Route names and Blade components should stay kebab-case (`<x-menu-card>`), and env keys remain upper snake case.

## Testing Guidelines
Create `*Test.php` classes matching the namespace of the code under test (e.g., `Tests\Feature\MenuTest`). Favor feature tests for HTTP endpoints interacting with Eloquent models, seeding data with factories. Run `php artisan test --coverage` before pushing to ensure new paths are exercised, and include regression tests when fixing bugs.

## Commit & Pull Request Guidelines
History currently shows very short messages (e.g., `ok`). Improve clarity by writing imperative subjects with a scope prefix, such as `feat(menu): add specials seeder`. Each PR should summarize functional changes, reference related issues, list key commands run, and attach UI screenshots or API samples when relevant. Confirm that migrations, seeds, and queue workers run cleanly before requesting review.

## Security & Configuration Tips
Never commit `.env` or `storage/` contents. Rotate `APP_KEY` with `php artisan key:generate` if secrets leak, and use Sail or Valet HTTPS URLs when testing auth callbacks. Restrict file permissions on uploaded assets and sanitize user-supplied HTML using Laravel's built-in `e()` helpers.
