SocialSuite – Overlay v3 (Facebook thực tế: OAuth + Page tokens + Webhook + Send Queue)

1) CÀI PHP PACKAGES (bắt buộc)
   composer require laravel/socialite:^5 guzzlehttp/guzzle:^7

2) CẤU HÌNH .env
   APP_URL=https://mmo.homes
   # Meta App
   META_APP_ID=your_meta_app_id
   META_APP_SECRET=your_meta_app_secret
   META_VERIFY_TOKEN=your_verify_token
   META_GRAPH_VERSION=v18.0
   # Tên callback chính xác:
   # https://mmo.homes/auth/facebook/callback

3) KHAI BÁO SOCIALITE (config/services.php)
   'facebook' => [
      'client_id' => env('META_APP_ID'),
      'client_secret' => env('META_APP_SECRET'),
      'redirect' => env('APP_URL').'/auth/facebook/callback',
   ],

4) ROUTES mới đã thêm (routes/web.php):
   GET  /auth/facebook/redirect   -> OAuthController@redirect
   GET  /auth/facebook/callback   -> OAuthController@callback
   POST /pages/import             -> OAuthController@importPages  (lưu page access tokens)
   POST /api/facebook/send        -> FacebookController@send (queued)
   POST /api/facebook/broadcast   -> FacebookController@broadcast (queued)
   GET/POST /webhook/facebook     -> WebhookController@handle (đã parse sự kiện)

5) MIGRATIONS mới:
   - 2025_01_01_000010_alter_pages_add_perms.php (thêm trường perms, subscribed)

6) QUEUE
   php artisan queue:table  (nếu chưa có)
   php artisan migrate
   php artisan queue:work   (cửa sổ riêng)

7) DEV
   npm run dev
   php artisan serve --host=mmo.homes --port=8000

8) FACEBOOK APP
   - Thêm Valid OAuth Redirect URI: https://mmo.homes/auth/facebook/callback
   - URL Webhook Callback: https://mmo.homes/webhook/facebook (verify token = META_VERIFY_TOKEN)
   - Chuyển App sang "Live" khi sẵn sàng. Trong chế độ dev chỉ admin/dev/role mới nhắn được.