# SocialSuite — Rebuild (Phase 1: Nền tảng sạch)

**Mục tiêu Phase 1**
- Kiến trúc sạch, module hoá sẵn cho Facebook.
- Migration chuẩn cho bảng: fb_users, fb_pages, fb_page_tokens.
- UI siêu tối giản: nút "Đăng nhập Facebook" (route stub) — OAuth sẽ implement ở Phase 2.
- Chuẩn XAMPP `http://localhost` (không cần HTTPS).

## Cách sử dụng (Overlay vào Laravel mới)
1) Tạo Laravel mới (Laravel 12.x):
   ```bash
   composer create-project laravel/laravel socialsuite
   ```

2) Giải nén **zip này** đè vào thư mục dự án (root Laravel).

3) Cập nhật `.env` (xem file `.env.example.local` trong zip này) — quan trọng:
   - `APP_URL=http://localhost/socialsuite/public` (hoặc phù hợp thư mục bạn)
   - DB_* theo MySQL/XAMPP của bạn
   - QUEUE_DATABASE đã cấu hình sẵn

4) Chạy lệnh:
   ```bash
   php artisan key:generate
   php artisan migrate
   php artisan queue:table && php artisan migrate  # nếu chưa có jobs table
   php artisan serve  # hoặc chạy qua Apache XAMPP
   ```

5) Mở: `http://localhost/socialsuite/public` (hoặc URL bạn cấu hình),
   bạn sẽ thấy trang chào + nút **Đăng nhập Facebook** (route stub).

## Ghi chú kỹ thuật
- Phase 1 **chưa** bật OAuth để tránh phụ thuộc package; Phase 2 sẽ add Facebook OAuth (Socialite/SDK) + callback.
- Bảng dữ liệu đã tách rõ: người dùng FB, trang FB, token.
- Queue preset: `database` driver để dùng được ngay với XAMPP.

## Phase 2 (next)
- Thêm Facebook OAuth (localhost HTTP được Facebook cho phép với domain `localhost`).
- Lưu token dài hạn, đồng bộ Pages, Dashboard sau login.

— Generated on 2025-10-22
