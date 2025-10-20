# SocialSuite — Robust Session Fix (2025_10_20_175957)

This patch **eliminates** both:
- `FacebookSDKException: Sessions are not active`
- `TypeError: Argument #1 ($session) must be of type Illuminate\Contracts\Session\Session, Illuminate\Session\SessionManager given`

### What's included
1) `app/Support/Facebook/LaravelPersistentDataHandler.php`  
   - Accepts **either** `SessionManager` or `Session Store` and normalizes to Store.
2) `app/Services/FacebookClient.php`  
   - Initializes SDK with the handler above.
3) `app/Http/Controllers/AuthController.php`  
   - Injects `FacebookClient` instead of raw SDK.

### Apply
1) Extract to your Laravel project root (overwrite).
2) Ensure:
   ```bash
   composer require facebook/graph-sdk:^5.1
   ```
3) `.env` (example):
   ```env
   APP_URL=https://mmo.homes
   FB_APP_ID=YOUR_APP_ID
   FB_APP_SECRET=YOUR_APP_SECRET
   FB_REDIRECT_URI=https://mmo.homes/auth/facebook/callback
   FB_GRAPH_VERSION=v19.0
   SESSION_DRIVER=database
   SESSION_DOMAIN=mmo.homes
   SESSION_SECURE_COOKIE=true
   ```
4) Clear caches:
   ```bash
   php artisan optimize:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   ```
5) Visit: `https://mmo.homes/auth/facebook/redirect`
