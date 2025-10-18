# SocialSuite Pages & Posts Pack
[SOCIALSUITE][GPT][2025-10-18 10:44 +07]

## Features
- Quản lý Page (đồng bộ từ Graph, hiển thị danh sách).
- Soạn & lưu bài viết (text/link/photo), đăng ngay hoặc hẹn giờ.
- Tự động đăng bài đã hẹn giờ qua command `socialsuite:publish-due` (đã schedule mỗi phút).
- UI thân thiện dạng "feed" ở trang posts.

## Files
- database/migrations/2025_10_18_100000_create_posts_table.php
- app/Models/FacebookPage.php
- app/Models/Post.php
- app/Http/Controllers/PageController.php
- app/Http/Controllers/PostController.php
- app/Console/Commands/PublishDuePosts.php
- app/Console/Kernel.php (đã thêm schedule)
- routes/pages_posts.php
- resources/views/pages/index.blade.php
- resources/views/posts/index.blade.php

## Install
1) Unzip to project root (allow overwrite).
2) Run migrations:
   php artisan migrate --path=database/migrations/2025_10_18_100000_create_posts_table.php
3) Include routes in routes/web.php (thêm cuối file):
   require __DIR__.'/pages_posts.php';
4) Clear caches:
   php artisan view:clear && php artisan route:clear && php artisan config:clear
5) Truy cập:
   /pages — danh sách Page
   /pages/{page}/posts — soạn & quản lý bài
6) Scheduler: để auto publish, chạy scheduler của Laravel (ví dụ trong dev):
   php artisan schedule:work
   hoặc cấu hình cron: * * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1

## Note
- Yêu cầu có bảng `facebook_pages` và có `access_token` cho từng page (đã có từ gói trước). 
- Endpoint đăng ảnh dùng /photos (với URL); đăng text/link dùng /feed.
