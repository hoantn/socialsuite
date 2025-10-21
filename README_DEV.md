# SocialSuite — Facebook Login DEV Pack (2025_10_21_071259)

Use this to make Facebook Login work smoothly on **http://localhost**.

## Install once
    composer require facebook/graph-sdk:^5.1 guzzlehttp/guzzle:^7

## .env (DEV)
APP_URL=http://localhost
FB_APP_ID=YOUR_APP_ID
FB_APP_SECRET=YOUR_APP_SECRET
FB_REDIRECT_URI=http://localhost/auth/facebook/callback
FB_GRAPH_VERSION=v19.0

SESSION_DRIVER=file
SESSION_DOMAIN=
SESSION_SECURE_COOKIE=false
SESSION_SAMESITE=lax

FB_SSL_VERIFY=false

## Clear caches
php artisan optimize:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

Open: http://localhost/auth/facebook/redirect
