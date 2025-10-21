# SocialSuite – DEV stable bundle (2025_10_21_084745)

Mục tiêu: **chấm dứt các lỗi lặt vặt** (SSL, session, state mismatch) khi chạy **localhost + XAMPP**.
Gói này thay thế/cập nhật các phần sau:

1) `app/Services/FacebookClient.php`
   - Ép Facebook SDK dùng **Guzzle** do chúng ta khởi tạo (nhận `verify` từ `.env`).
   - Dùng `LaravelPersistentDataHandler` để **không còn lỗi "Sessions are not active"**.
   - Có `setDefaultAccessToken()` helper.

2) `app/Support/Facebook/LaravelPersistentDataHandler.php`
   - Triển khai `PersistentDataInterface` dùng **Laravel session** (không dùng `session_start()` thô).

3) `app/Http/Controllers/AuthController.php`
   - Login redirect/callback **ổn định**: regenerate session, kiểm tra state, lưu user token.
   - Không gọi new SDK trực tiếp — **chỉ** dùng `FacebookClient`.

4) `routes/web.php` (snippet)
   - Đảm bảo các route OAuth nằm **trong web middleware**.

5) `config/facebook.php`
   - Config tập trung cho `FB_*`.

## .env (DEV)
```
APP_URL=http://localhost
SESSION_DRIVER=file

FB_APP_ID=your_app_id
FB_APP_SECRET=your_app_secret
FB_GRAPH_VERSION=v19.0

# DEV Windows/XAMPP: tránh lỗi CA
FB_SSL_VERIFY=false
FB_HTTP_TIMEOUT=30

# OAuth
FB_REDIRECT_URI=http://localhost/auth/facebook/callback
```

## Lệnh cần chạy
```
php artisan optimize:clear
php artisan config:clear
```

Sau khi áp dụng:
- Vào `http://localhost/auth/facebook/redirect` → popup FB → callback về `/auth/facebook/callback`.
- Không còn lỗi **SSL** và **Sessions are not active**; giảm mạnh lỗi **state mismatch**.

> Lưu ý: đảm bảo 2 route dưới **đúng group** middleware `web`.
