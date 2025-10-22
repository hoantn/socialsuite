# SocialSuite — Phase 3A Overlay (Multi-Page Post + History + Draft-ready)

## Tính năng
- **Compose đa Page**: chọn nhiều Page và đăng 1 nội dung (text, ảnh).
- **Lịch sử** theo Page: phân trang, hiển thị trạng thái published/error.
- **Chuẩn bị cho Draft/Schedule**: thêm cột `status` (draft/published/error) và `error_code`, `error_message` (không phá dữ liệu cũ).

## Cài đặt
1) Giải nén overlay này **đè** vào dự án.
2) Chạy migrate (thêm cột mới cho `fb_posts`):
```bash
php artisan migrate
```
3) Vào `http://localhost/compose` để đăng nhiều Page cùng lúc.
   Hoặc vào trang Page riêng `/pages/{pageId}` để xem lịch sử và đăng đơn.

## Ghi chú
- Ảnh: 1 ảnh/lần đăng (giai đoạn này). Nhiều ảnh sẽ cập nhật ở Phase 5.
- Kết quả đăng nhiều Page: hiển thị tổng kết số thành công/thất bại; chi tiết lưu trong `fb_posts`.
