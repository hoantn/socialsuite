# SocialSuite — Phase 3 (Page Management like botcake.io)

## Tính năng
- Sau khi đăng nhập → hiển thị danh sách Pages.
- Click vào 1 Page → **Trang quản lý Page**.
- Tạo bài đăng **text** hoặc **text + ảnh** (đăng ngay).
- Xem **post gần đây** của Page.
- Lưu **lịch sử post** vào DB (bảng `fb_posts`).

## Cài đặt
1) Giải nén ZIP này **đè** vào dự án.
2) Tạo bảng lịch sử post:
```bash
php artisan migrate
```
3) Vào `http://localhost/pages` → chọn Page để quản lý.

## Quyền / Scopes Facebook cần
- `pages_manage_posts`, `pages_read_engagement`, `pages_read_user_content`, `pages_show_list`.
(đã xin từ Phase 2)
