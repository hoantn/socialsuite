# SocialSuite Facebook Real Patch

## 1) Packages
```
composer require facebook/graph-sdk
```

## 2) .env
```
FB_APP_ID=your_fb_app_id
FB_APP_SECRET=your_fb_app_secret
FB_OAUTH_REDIRECT=https://yourdomain.tld/api/facebook/callback
FB_VERIFY_TOKEN=your_verify_token
```

## 3) Migrate
```
php artisan migrate
```

## 4) Endpoints
- POST `/api/facebook/callback` body: `{access_token: short_lived_token}` -> lưu long-lived user token
- POST `/api/facebook/import` (hoặc `{ token: long_token }`) -> lưu pages thật vào DB
- GET `/api/facebook/pages` -> danh sách page
- POST `/api/facebook/subscribe` body: `{page_id: "..."}`
- Webhook: `/webhook/facebook` (GET verify + POST events)
