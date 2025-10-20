# SocialSuite — Fix Facebook SDK "Sessions are not active" (2025_10_20_143607)

Files included:
1) app/Support/Facebook/LaravelPersistentDataHandler.php
2) app/Services/FacebookClient.php
3) app/Http/Controllers/AuthController.php

How to use:
- Extract to your Laravel project root (overwrite when asked).
- composer require facebook/graph-sdk:^5.1
- Set .env (FB_APP_ID, FB_APP_SECRET, FB_REDIRECT_URI, FB_GRAPH_VERSION).
- php artisan optimize:clear && php artisan config:clear && php artisan route:clear && php artisan view:clear
- Visit https://mmo.homes/auth/facebook/redirect
