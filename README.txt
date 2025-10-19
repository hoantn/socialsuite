SocialSuite – Full Overlay v2 (Tailwind v4 fix + FB stubs)
---------------------------------------------------------
1) Ghi đè vào dự án Laravel 11.
2) Cài PHP packages (1 lần):
   composer require inertiajs/inertia-laravel guzzlehttp/guzzle
   php artisan inertia:middleware
3) Node packages:
   npm install
4) DB SQLite + APP KEY + migrate + seed:
   type nul > database\database.sqlite
   copy .env.example .env
   php artisan key:generate
   php artisan migrate --seed
5) Chạy dev:
   npm run dev
   php artisan serve --host=mmo.homes --port=8000

HTTPS: APP_URL=https://mmo.homes (đã cấu hình trong .env.example)

FB Send API (thật): POST /api/facebook/send { psid, text }
- Cần: META_PAGE_ACCESS_TOKEN trong .env
Webhook: /webhook/facebook (GET verify + POST receive)