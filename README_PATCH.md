# SocialSuite — Page‑Centric Facebook Patch (2025_10_20_053947)

This patch adds a **Page‑centric** architecture and production guards:
migrations, models, controllers, middleware, jobs, webhook handler,
rate‑limiting per Page, Messenger 24h policy checks, health dashboard,
and a Data Deletion page.

## How to apply
1) **Backup** your project first.
2) Download and extract this zip to the root of your Laravel project (same level as `app/`, `routes/`, `database/`), allowing it to **overwrite** or **add** files.
3) Ensure PHP cURL/SSL trust store (Windows/XAMPP):
   - Download `cacert.pem` (from curl.se) and set in `php.ini`:
     - `curl.cainfo="D:\xampp\php\extras\ssl\cacert.pem"`
     - `openssl.cafile="D:\xampp\php\extras\ssl\cacert.pem"`
   - Restart Apache.
4) Install deps & migrate:
   ```bash
   composer install
   php artisan key:generate
   php artisan migrate --path=database/migrations/fb_page_centric
   php artisan db:seed --class=FbPageCentricSeeder
   ```
5) Set `.env` (see **.env additions** below), then:
   ```bash
   php artisan optimize:clear
   php artisan serve   # or your XAMPP VirtualHost
   ```
6) Visit:
   - `/auth/facebook/redirect` → login → `/pages` → **Đồng bộ từ Facebook**
   - `/pages/{page_id}/settings` to configure shared Page settings
   - `/health` for system status
   - `/privacy/data-deletion` for Meta Data Deletion instructions

### .env additions
```
FB_APP_ID=your_app_id
FB_APP_SECRET=your_app_secret
FB_REDIRECT_URI=http://localhost/socialsuite/public/auth/facebook/callback
FB_GRAPH_VERSION=v19.0

QUEUE_CONNECTION=redis
REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

If you don't have Redis yet, temporarily set `QUEUE_CONNECTION=database`
and run `php artisan queue:table && php artisan migrate` (core migrations).
(You can keep Redis for later; jobs still work with database queue.)

---

## What’s included
- **Migrations (scoped under `database/migrations/fb_page_centric/`)**
  - `fb_accounts`, `fb_pages`, `account_page`, `page_configs`, `webhook_subscriptions`
- **Models** for those tables
- **Controllers**:
  - `AuthController` (OAuth login/callback)
  - `PageController` (sync `/me/accounts` + listing)
  - `PageConfigController` (per‑Page shared config)
  - `WebhookController` (Facebook Webhooks verify + receive)
  - `HealthController` (status dashboard)
- **Middleware**:
  - `FbAuth` (require login)
  - `EnsureHasPageAccess` (guard routes with page_id)
  - `MessengerPolicyMiddleware` (24h window & message tag checks for send actions)
- **Jobs** (queued) using **Page Access Token** from `fb_pages`:
  `PostToPageJob`, `ReplyCommentJob`, `SendPageMessageJob`, `LikeCommentJob`
- **Service**: `FacebookClient` wrapper for Graph calls
- **Views**: pages list, page settings, health, privacy/data-deletion
- **Routes** additions in `routes/web.php` (idempotent — merge carefully)
- **Kernel scheduler hook** sample to resubscribe/health-check

> All write/side‑effect operations are channeled through Jobs to keep UI responsive and to enforce per‑Page rate limits with backoff.

---

## Notes
- This patch won’t delete your legacy auth automatically, but pages UI is locked behind `FbAuth` and `EnsureHasPageAccess`. You may remove legacy routes/views later.
- Permission Matrix: ensure your Facebook App is granted `pages_show_list, pages_manage_metadata, pages_read_engagement, pages_read_user_content, pages_manage_posts, pages_messaging` as needed via App Review.
- Broadcasting beyond 24h needs proper tags or Sponsored Messages per Meta policy.
- Consider enabling Dockerized Redis for Windows later; database queue is acceptable to start.

Good luck 🚀
