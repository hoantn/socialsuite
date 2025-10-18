# SocialSuite – Facebook-only FULL RESET
**Generated:** 2025-10-18 13:35 +07

## Cài đặt
1) Giải nén vào root dự án.
2) Chạy migration:
```bash
php artisan migrate --path=database/migrations/2025_10_18_140000_full_schema_fb_only.php
```
3) Thêm vào cuối `routes/web.php`:
```php
require __DIR__.'/facebook_only_full_reset.php';
```
4) Tại OAuth callback, POST `access_token` tới `/auth/facebook/bind`. Thành công → chuyển tới `/me`.
