# Deviation Notes

## Project bootstrap

No existing Laravel project or uploaded migration/model files were found on disk. The schema was inferred from `~/Downloads/tailor_db_schema_erd.html` (tailor shop management domain) and migrations/models were created to match that ERD.

## Framework version

Laravel **13** was installed (latest skeleton). Application structure follows Laravel 11+ conventions (`bootstrap/app.php`, no `Http/Kernel.php`).

## User model linkage

The ERD lists a separate `customers` table and a `users` table with an enum role. Spatie roles replace the enum; a nullable `customer_id` on `users` links customer-role accounts to their `customers` record for scoped API access.

## UUID primary keys

Domain tables use UUID primary keys per the ERD. The default Laravel `users` migration was updated to UUID as well for consistency with `production_tasks.assigned_to`.

## Docker runtime

`docker-compose.yml` and `docker/app/Dockerfile` are provided as specified. Docker was not available in the build environment; run `docker compose up --build` locally for PostgreSQL, Redis, and the app container.

## JWT environment variables

`JWT_ACCESS_TTL` and `JWT_REFRESH_TTL` from `.env` are mapped to `config/jwt.php` `ttl` and `refresh_ttl` after publishing the JWT config.
