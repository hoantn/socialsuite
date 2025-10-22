# SocialSuite — Phase 4 (Scheduler + Queue)

## Tính năng
- Lên lịch đăng bài (text hoặc ảnh) cho 1 Page.
- Tác vụ tự động chạy mỗi phút: quét bài `due` và đẩy vào Queue.
- Job xử lý đăng: retry, backoff; ghi trạng thái và lỗi FB.
- Trang quản lý lịch: tạo lịch, hủy lịch, xem danh sách (queued/processing/published/failed).

## Cài đặt nhanh
1) **Bật queue DB**:
   ```bash
   php artisan queue:table
   php artisan migrate
   ```
   Trong `.env`:
   ```dotenv
   QUEUE_CONNECTION=database
   ```
2) Áp overlay này (giải nén đè), sau đó chạy migrate thêm bảng lịch:
   ```bash
   php artisan migrate
   ```
3) Nạp lệnh schedule (đã cài trong `app/Console/Kernel.php`):
   - Windows (XAMPP): Tạo Task mỗi phút chạy:
     ```
     php artisan schedule:run
     ```
   - Mở worker:
     ```
     php artisan queue:work --tries=3 --backoff=15
     ```
4) Vào **/schedule** để tạo/lưu lịch. Hoặc vào trang Page để tạo lịch cho Page đó.

## Gợi ý sử dụng
- `publish_at` lưu theo timezone bạn chọn; Dispatcher sẽ convert về UTC.
- Ảnh: 1 ảnh (jpg/png/gif, <=5MB). Nhiều ảnh sẽ hỗ trợ ở Phase 5.
