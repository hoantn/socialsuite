SocialSuite – Full Overlay (Laravel 11 + Inertia + Vue 3 + Tailwind + SQLite)
1) Giải nén ghi đè vào dự án Laravel 11.
2) Cài gói:
   composer require inertiajs/inertia-laravel
   php artisan inertia:middleware
   npm i vue @inertiajs/vue3 @vitejs/plugin-vue tailwindcss postcss autoprefixer
3) DB:
   type nul > database\database.sqlite
   copy .env.example .env
   php artisan key:generate
   php artisan migrate --seed
4) Chạy:
   npm run dev
   php artisan serve --host=mmo.homes --port=8000
Đăng nhập (seed): admin@mmo.homes / password