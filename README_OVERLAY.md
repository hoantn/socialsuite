# SocialSuite — Full Overlay (Fix 'Target class [view] does not exist') — 2025_10_20_062735

This overlay contains ready-to-drop files to stabilize your app and the Page‑centric Facebook integration.
It includes:
- A clean `routes/web.php` to avoid syntax errors.
- Minimal Blade layout and views.
- Controllers/Service read ENV directly (no services.php edit).

## Apply
1) Backup your project.
2) Extract this zip to project root (overwrite when asked).
3) Ensure `composer require facebook/graph-sdk:^5.1` is installed.
4) Clear caches:
   php artisan optimize:clear
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
5) Migrate (if needed):
   php artisan migrate --path=database/migrations/fb_page_centric
   php artisan db:seed --class=FbPageCentricSeeder
6) Set .env: FB_APP_ID, FB_APP_SECRET, FB_REDIRECT_URI, FB_GRAPH_VERSION
7) Visit /auth/facebook/redirect → /pages
