# ServeHub

Full-stack local services booking platform — **one Laravel app** serving
both the API and the React (Vite) UI. This is a complete, ready-to-run
Laravel 11 project (not a code-only overlay): `composer.json`, `artisan`,
`bootstrap/`, `public/`, `config/` are all here, wired up and ready.

```
Route → Controller/{Entity} → Service/{Entity} (Interface) → Repository/{Entity} (Contract) → Eloquent Model → PostgreSQL
```

See `README_INTEGRATION.md` for the full backend architecture write-up.

## Layout

```
app/            Controllers, Services, Repositories, Models, Enums (per-entity folders)
bootstrap/      Laravel bootstrap: app.php (routing/middleware), providers.php
config/         Standard Laravel config, defaulted to PostgreSQL + file cache/sessions
database/       Migrations, seeders, factories
public/         Front controller (index.php) — Laravel's web root
resources/js/   React source (App.jsx, pages/, components/, context/, api/)
resources/views/app.blade.php   The single Blade shell React mounts into
routes/         api.php (per-entity route files) + web.php (SPA catch-all route)
vite.config.js  laravel-vite-plugin + @vitejs/plugin-react
```

`RepositoryServiceProvider` (bound in `bootstrap/providers.php`) wires
every Repository Contract to its Eloquent implementation and every Service
Interface to its Service implementation. The `role` middleware alias
(used in routes like `->middleware('role:admin')`) is registered in
`bootstrap/app.php`.

## 1. Install dependencies

```bash
composer install
npm install
```

## 2. Configure the environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` for your PostgreSQL credentials (defaults shown):

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=servehub
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

## 3. Migrate & seed

```bash
php artisan migrate --seed
```

This creates every ServeHub table (users, provider_profiles,
service_categories, services, availabilities, bookings, reviews,
notifications, payments, personal_access_tokens) and seeds:
- an admin user: `admin@servehub.test` / `password`
- 10 default service categories

(`npm run setup` does the `.env` copy + `key:generate` + `migrate --seed`
in one command.)

## 4. Run it

```bash
npm run dev
```

This runs `php artisan serve` (`:8000`) and `vite` (HMR for React) together
in one terminal. Open **`http://127.0.0.1:8000`** — Laravel serves the
page and API from the same origin, so there's no CORS setup needed.
`npm run build` produces production assets Laravel serves directly (no
Vite process needed in prod).

## Notes

- API routes are unprefixed in the `routes/*.php` files (e.g. `/services`);
  Laravel mounts them under `/api` automatically, matching
  `resources/js/api/axios.js`'s default `baseURL` of `/api`.
- `routes/web.php` is a single catch-all that returns the SPA shell for
  every non-`/api` path — React Router takes over from there client-side.
- Auth uses Sanctum personal access tokens (Bearer token in
  `localStorage`, sent via `Authorization` header) — not cookie-based SPA
  auth, so no CSRF/stateful-domain config is needed.

**Prerequisites:** PHP 8.2+, Composer, PostgreSQL, Node.js 18+.
