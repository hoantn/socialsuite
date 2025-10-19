# SocialSuite SPA + API patch (SQLite-first)

This bundle gives you a clean Vue 3 SPA (Vite) + minimal Laravel API
so you can iterate quickly (HTTP dev friendly, SQLite-first).

## What's included
- `resources/views/app.blade.php` – SPA entry (uses `@vite('resources/js/app.js')`)
- `resources/js/...` – Vue 3 + router + simple pages (Dashboard, Pages, Inbox, Flows, Broadcasts, Settings)
- `routes/web.php` – catch-all to render SPA
- `routes/api.php` – `/api/facebook/pages`, `/api/facebook/subscribe`
- `app/Http/Controllers/Api/FacebookController.php` – DEV returns demo pages if no `FB_USER_TOKEN`
- `database/migrations/...create_socialsuite_core.php` – tables for pages & provider tokens
- `vite.config.js` – HTTP HMR defaults

## Quick start (fresh or existing)
1. Merge/overwrite these files into your Laravel project root.
2. Ensure **SQLite** is enabled in `.env`:
```
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```
Create file if missing:
```
mkdir -p database && type NUL > database/database.sqlite  (Windows)
# or: touch database/database.sqlite (macOS/Linux)
```
3. Install & build frontend:
```
npm install
npm run dev   # for local dev (HMR)
# or
npm run build # for production
```
4. Run migrations:
```
php artisan migrate
```
5. Visit: http://localhost or your local domain.
   - Dashboard should load.
   - `/pages` should list demo pages (unless you set `FB_USER_TOKEN` in `.env`).

## Real Facebook login (later)
- Wire OAuth to get a **user access token** with `pages_manage_metadata`, `pages_read_engagement`, `pages_show_list`.
- Save token into DB (`user_providers`) and call Graph API in `FacebookController@listPages`.
- Page subscribe: POST `/{page-id}/subscribed_apps` with page token.

Happy building!
