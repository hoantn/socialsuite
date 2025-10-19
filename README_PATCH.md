
# SocialSuite Patch (SQLite + Facebook OAuth + Pages Import)

This patch drops into an **existing Laravel 10/11/12** project and adds:

- SQLite-ready migrations and models
- Facebook OAuth (Socialite) scaffolding
- `/api/pages` + `/api/pages/import` endpoints
- `/auth/facebook/redirect` + `/auth/facebook/callback` routes
- A safe catch‑all SPA route that serves `resources/views/public.blade.php`

> You can overwrite files in your project with the ones in this zip.

## Quick install

1) **Backup** your project, then extract this zip to the project root and allow overwrite.

2) Ensure SQLite is used (create DB file and set `.env`):

- Windows: `type nul > database\database.sqlite`
- macOS/Linux: `touch database/database.sqlite`

Add/Update in `.env`:

```
APP_URL=http://mmo.homes
SESSION_DOMAIN=mmo.homes
SANCTUM_STATEFUL_DOMAINS=mmo.homes

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

FACEBOOK_CLIENT_ID=YOUR_ID
FACEBOOK_CLIENT_SECRET=YOUR_SECRET
FACEBOOK_REDIRECT_URI=http://mmo.homes/auth/facebook/callback
```

3) **Composer** (Socialite + dependencies):

```
composer require laravel/socialite:^5.12 -W
php artisan migrate
```

4) Start server & Vite. Visit `/pages` to connect Facebook and import Pages.
