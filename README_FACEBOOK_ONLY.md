# SocialSuite - Facebook-only Core Pack
Install:
1) Unzip to project root (allow overwrite)
2) Migrate:
   php artisan migrate --path=database/migrations/2025_10_18_130000_create_facebook_accounts.php
   php artisan migrate --path=database/migrations/2025_10_18_130100_create_page_memberships.php
   php artisan migrate --path=database/migrations/2025_10_18_130200_alter_pages_and_posts_for_fb_only.php
3) Append to routes/web.php:
   require __DIR__.'/facebook_only.php';
4) OAuth Facebook như cũ, sau khi có user access token, POST tới /auth/facebook/bind với access_token
   -> session có fb_account_id -> /pages
