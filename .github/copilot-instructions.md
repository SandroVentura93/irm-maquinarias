# AI Coding Agent Instructions for ERP Laravel Project

This is a Laravel 8 ERP/point-of-sale system. Use this guide to quickly understand architecture, workflows, and conventions for productive AI coding.

## Architecture Overview

- **MVC structure:** Controllers in `app/Http/Controllers`, models in `app/Models`, services in `app/Services`.
- **Routing:** Main UI routes in `routes/web.php` (auth-protected), API endpoints in `routes/api.php` (Sanctum guard).
- **Persistence:** Eloquent models for tables like `ventas`, `productos`, `configuraciones`, `monedas`. See `app/Models/Venta.php` for relationships and soft deletes.
- **Reports/Exports:** `app/Services/ReporteService.php` generates PDFs (barryvdh/laravel-dompdf) and Excel (maatwebsite/excel) using query filters and view templates.
- **Configuration:** Use `App\Models\Configuracion::obtener/establecer` for config values (typed, not raw DB access).

## Key Conventions & Patterns

- **Spanish domain language:** All code, variables, and views use Spanish (e.g., `tipo_comprobante`, `venta->detalles`).
- **Soft deletes:** Used on `Venta` (`use SoftDeletes`). Always preserve deleted-state logic.
- **Monetary fields:** Use decimal casts for money fields (`subtotal`, `total`). Dates use `datetime` casts.
- **File storage:** Use Laravel filesystem (`storage/app/public`). See logo upload in `ConfiguracionController@actualizarGeneral`.
- **Backups:** DB backup/restore uses shell commands (`mysqldump`, `mysql`) in `ConfiguracionController`. Require human review for changes.

## Developer Workflows

- **Install PHP deps:** `composer install`
- **Run migrations/seeds:** `php artisan migrate --seed`
- **Dev server:** `php artisan serve` or `php -S localhost:8000 server.php`
- **Frontend assets:** `npm install` then `npm run dev` or `npm run production`
- **Run tests:** `./vendor/bin/phpunit` or `php artisan test`

## Integration Points

- **PDF:** Use `PDF::loadView()` (barryvdh/laravel-dompdf)
- **Excel:** Use `Excel::download()` (maatwebsite/excel)
- **HTTP client:** `guzzlehttp/guzzle` available
- **Auth:** Laravel auth and `laravel/sanctum` for API

## Safety & Testing Guidance

- Prefer new methods/tests over in-place edits to core flows
- Always preserve Spanish labels and user-facing strings
- Update model casts and code when changing DB columns
- Require human review for backup/file storage/DB CLI changes

## Example Tasks

- **Validate stock endpoint:** See `VentaController::validarStock` (validates request, loads `Producto`, returns JSON)
- **Report filter:** See `ReporteService::generarReporteVentas` (add query filters, return PDF/Excel)
- **Config read/write:** Use `Configuracion::obtener('clave')` and `Configuracion::establecer('clave', $valor)`

For more detail on any area (tests, CI, components), ask to expand this file.

