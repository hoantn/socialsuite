# SocialSuite Homepage Pack
[SOCIALSUITE][GPT][2025-10-18 10:00 +07]

Files included:
- routes/web.php
- app/Http/Controllers/HomeController.php
- resources/views/layouts/app.blade.php
- resources/views/home.blade.php
- resources/views/sitemap.blade.php
- resources/views/auth/login.blade.php
- resources/views/auth/register.blade.php
- public/robots.txt

How to install:
1) Unzip into project root (allow overwrite).
2) Ensure `APP_URL` and session config are correct.
3) Clear caches: `php artisan view:clear && php artisan route:clear && php artisan config:clear`.
4) Visit `/` for homepage, `/login`, `/register`, `/sitemap.xml`.
