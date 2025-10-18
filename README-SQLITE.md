# SQLite-first Setup (Windows/XAMPP)

This patch makes your Laravel project use **SQLite** for local development to keep things simple and fast.

## Steps

1) Create the SQLite file (empty file is fine):
   - PowerShell:
     ```powershell
     New-Item -ItemType File 'D:\xampp\htdocs\socialsuite\database\database.sqlite' -Force | Out-Null
     ```

2) Copy `.env.sqlite.example` to your project's `.env`:
   ```powershell
   Copy-Item '.\.env.sqlite.example' 'D:\xampp\htdocs\socialsuite\.env' -Force
   ```
   - Update `APP_URL`, and fill `META_*` values.

3) Generate key & migrate:
   ```powershell
   cd D:\xampp\htdocs\socialsuite
   php artisan key:generate
   php artisan migrate
   ```

4) Queue for dev:
   - Default in this patch is `QUEUE_CONNECTION=sync` to avoid SQLite 'database is locked' issues.
   - When you want to test async jobs on SQLite:
     ```powershell
     php artisan queue:table
     php artisan migrate
     php artisan queue:work --tries=3
     ```

5) Run server:
   ```powershell
   php artisan serve --host=127.0.0.1 --port=8001
   ```
   Visit `http://127.0.0.1:8001` (or your vhost).

## Notes

- Keep `DB_DATABASE` as **absolute Windows path** to avoid surprises.
- SQLite handles concurrency poorly for heavy webhook loads; for production switch to MySQL or Postgres.
- Everything else (OAuth, Webhooks, Controllers) stays the same.