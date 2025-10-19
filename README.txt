SocialSuite Patch (Logs + Debug) — v4a

Mục tiêu
- Hiển thị danh sách Page sau khi đăng nhập Facebook.
- Ghi log chi tiết khi gọi Graph API để xác định nguyên nhân nếu /me/accounts trả rỗng hoặc lỗi.
- Bổ sung scope: pages_read_engagement (một số case cần để trả đủ dữ liệu Page).
- Thêm route debug /dev/fb/check để xem JSON từ Facebook ngay trên trình duyệt.

Áp dụng
1) Giải nén toàn bộ và GHI ĐÈ lên dự án.
2) Chạy: php artisan config:clear && php artisan route:clear
3) Dev: npm run dev  (hoặc build: npm run build)
4) Thu hồi quyền app, rồi login lại: /auth/facebook/redirect
5) Nếu vẫn không thấy Page: mở /dev/fb/check và check file storage/logs/laravel.log

ENV
APP_URL=https://mmo.homes
META_APP_ID=...
META_APP_SECRET=...
META_VERIFY_TOKEN=...
META_GRAPH_VERSION=v18.0

Files patched
- app/Services/MetaGraph.php            (throw() + log-friendly)
- app/Http/Controllers/OAuthController.php  (OAuth không dùng Socialite, thêm pages_read_engagement, log)
- routes/web.php                         (thêm /dev/fb/check và giữ các route v4)