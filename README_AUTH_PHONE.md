# Simplified Auth + Phone (Drop-in)
[SOCIALSUITE][GPT][2025-10-18 10:33 +07]

## Files
- database/migrations/2025_10_18_000020_add_phone_and_relax_email_on_users.php
- app/Http/Controllers/AuthController.php
- app/Models/User.php
- resources/views/auth/register.blade.php
- resources/views/auth/login.blade.php

## Install
1) Unzip to project root (allow overwrite).
2) Run migration:
   php artisan migrate --path=database/migrations/2025_10_18_000020_add_phone_and_relax_email_on_users.php
3) Clear caches (optional):
   php artisan view:clear && php artisan config:clear
4) Test:
   - /register  (username + password bắt buộc; email/phone tuỳ chọn)
   - /login     (đăng nhập bằng username/email/phone + password)
