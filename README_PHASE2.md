# SocialSuite — Phase 2 (Facebook OAuth + Pages Sync)

## Tính năng
- Đăng nhập bằng **Facebook OAuth** (Socialite).
- Lưu **long-lived user access token**.
- Đồng bộ **Pages**: id, name, category, picture, connected_ig_id, kèm **page access token**.
- Dashboard hiển thị danh sách Pages sau khi login.

## Yêu cầu cài đặt
Trong thư mục dự án Laravel sạch (đã nhận Phase 1):
```bash
composer require laravel/socialite ^5.14 socialiteproviders/facebook ^5.15 guzzlehttp/guzzle ^7.9
```

Bật providers (không cần nếu dùng auto-discovery, nhưng thêm để rõ ràng):
- `config/app.php` → phần `providers` (nếu thiếu): `Laravel\Socialite\SocialiteServiceProvider::class`

## Cấu hình Facebook App
Facebook Developer → App của bạn:
- **Valid OAuth Redirect URIs**: `http://localhost/auth/facebook/callback`

## ENV mẫu (chỉnh .env)
```
APP_URL=http://localhost

FACEBOOK_CLIENT_ID=your_app_id
FACEBOOK_CLIENT_SECRET=your_app_secret
FACEBOOK_REDIRECT_URI=http://localhost/auth/facebook/callback
FACEBOOK_GRAPH_VERSION=v20.0
```

## Cài đặt mã nguồn Phase 2
1) Giải nén ZIP này **đè lên** dự án (overlay).
2) `php artisan config:clear && php artisan route:clear`
3) Truy cập `http://localhost` → bấm **Đăng nhập Facebook**.

## Ghi chú
- Token người dùng được đổi sang **long-lived** (60 ngày) qua endpoint OAuth Graph.
- Các Page cùng quyền sẽ lưu về bảng `fb_pages` + `fb_page_tokens`.
- Bạn có repo chính ở `https://github.com/hoantn/socialsuite`; mọi lần triển khai tiếp theo, mình sẽ bám repo này để đối chiếu và phát hành patch.
— Generated 2025-10-22
