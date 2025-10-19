
# SocialSuite Patch v1 (SQLite + HTTP)

## Packages
```
composer require laravel/socialite:^5.12 guzzlehttp/guzzle:^7.8 --with-all-dependencies
```

## .env
```
APP_URL=http://mmo.homes
APP_DEBUG=true

DB_CONNECTION=sqlite
VITE_DEV_SERVER_URL=http://mmo.homes:5173

FACEBOOK_CLIENT_ID=your_app_id
FACEBOOK_CLIENT_SECRET=your_app_secret
FACEBOOK_REDIRECT=http://mmo.homes/auth/facebook/callback

FB_WEBHOOK_VERIFY=supersecret_verify_token
```

Create DB:
```
type NUL > database/database.sqlite
```

Add to `config/services.php`:
```php
'facebook' => [
  'client_id' => env('FACEBOOK_CLIENT_ID'),
  'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
  'redirect' => env('FACEBOOK_REDIRECT'),
],
```

Migrate:
```
php artisan migrate
```

Endpoints:
- `/` SPA
- `/auth/facebook/redirect` → `/auth/facebook/callback`
- `GET /api/pages` + `POST /api/facebook/import-pages`
- Webhook: `GET|POST /webhooks/facebook`

Facebook App:
- OAuth Redirect: `http://mmo.homes/auth/facebook/callback`
- Webhook URL: `http://mmo.homes/webhooks/facebook`
- Scopes: `pages_show_list, pages_manage_metadata, pages_messaging`
