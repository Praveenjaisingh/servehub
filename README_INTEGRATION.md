# ServeHub Backend — Architecture Guide

This is the **backend layering reference** for ServeHub's `app/` folder.
For setup and run instructions (including the React/Vite frontend), see
the top-level `README.md`.

## Architecture

```
Route → Controller/{Entity} → Service/{Entity} (Interface) → Repository/{Entity} (Contract) → Eloquent Model → PostgreSQL
```

Every module is organised **per entity**, each with its own folder:

- **Controller** (`app/Http/Controllers/{Entity}/{Entity}Controller.php`) —
  only validation (via `FormRequest` or inline `$request->validate()`) and a
  `try/catch`. Never touches the DB or contains business rules. Depends on
  the entity's Service Interface.
- **Service Interface** (`app/Services/{Entity}/{Entity}Interface.php`) —
  the type Controllers depend on. Lets you swap implementations or mock in
  tests.
- **Service** (`app/Services/{Entity}/{Entity}Service.php`) — implements
  the interface. **All business logic lives here**: authorization checks,
  status-transition rules, aggregation, orchestration between repositories.
  Depends on one or more Repository Contracts (never touches Eloquent
  directly).
- **Repository Contract** (`app/Repositories/{Entity}/{Entity}Contract.php`)
  — the type Services depend on for data access.
- **Repository Eloquent** (`app/Repositories/{Entity}/{Entity}Eloquent.php`)
  — implements the contract. The only layer that talks to Eloquent models
  directly: builds queries, applies scopes (`scopeActive`, `scopeSearch`,
  …), calls thin static finders (`findByEmail`, `findBySlug`).
- **Eloquent Model** (`app/Models/*.php`) — relationships, query scopes, and
  thin static finders live here — no business rules.
- **Helpers** (`app/Helpers/`) — cross-cutting extras that don't belong to
  any one layer: `ApiResponse`, `api_success()/api_error()`,
  `generate_unique_slug()`, `distance_between_km()`, `booking_reference()`.

Routes follow the same split: each entity has its own file under
`routes/` (`auth.php`, `user.php`, `service.php`, `booking.php`, …), and
`routes/api.php` just `require`s each of them.

Every Repository Contract is bound to its concrete Eloquent implementation,
and every Service Interface to its concrete Service implementation, in
`app/Providers/RepositoryServiceProvider.php`.

Setup, database, and run instructions (including the React/Vite frontend)
live in the top-level `README.md` — `composer install`, `npm install`,
`.env`, `php artisan migrate --seed`, `npm run dev`.

Seeding (`php artisan migrate --seed`, part of `npm run setup`) creates
every ServeHub table (users, provider_profiles, service_categories,
services, availabilities, bookings, reviews, notifications, payments) and
seeds an admin user (`admin@servehub.test` / `password`) plus 10 default
service categories.

## Extending a module (recipe)

To add a new feature (e.g. "Payment"), follow the same 6 files every time:

1. `app/Repositories/Payment/PaymentContract.php` — declare data-access methods.
2. `app/Repositories/Payment/PaymentEloquent.php implements PaymentContract`
   — queries against `Payment::...` / other Eloquent models.
3. `app/Services/Payment/PaymentInterface.php` — declare business methods.
4. `app/Services/Payment/PaymentService.php implements PaymentInterface` —
   business logic, calling `PaymentContract` for data access.
5. `app/Http/Controllers/Payment/PaymentController.php` — inject the
   Service Interface, validate via a `FormRequest`, `try/catch`, delegate
   to the service.
6. Bind `PaymentContract → PaymentEloquent` and `PaymentInterface →
   PaymentService` in `RepositoryServiceProvider::$bindings`, then add a
   `routes/payment.php` and `require` it from `routes/api.php`.

## Notes

- Passwords are hashed via `Hash::make()` in the Service layer (never the
  Controller or Model).
- Authorization (e.g. "only the booking's provider can update its status")
  lives in the Service layer using `Illuminate\Auth\Access\AuthorizationException`,
  which the base `Controller::handleException()` maps to a 403 JSON response.
- `Illuminate\Validation\ValidationException` → 422,
  `Symfony\...\NotFoundHttpException` → 404, anything else → 500. This
  mapping is centralised once in `app/Http/Controllers/Controller.php` so
  every controller's catch block stays a single line.
- Postgres-specific bits used in the migrations/models: `jsonb` column for
  `provider_profiles.skills`, `ilike` for case-insensitive search scopes.
