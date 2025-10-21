# HTTP localhost DEV Fix

This zip makes Facebook Login work on http://localhost for development.

Files:
- app/Services/FacebookClient.php (DEV-friendly, no Composer CaBundle)
- app/Support/Facebook/LaravelPersistentDataHandler.php (robust handler)
- app/Http/Controllers/AuthController.php (reference)

Require:
    composer require facebook/graph-sdk:^5.1 guzzlehttp/guzzle:^7

.env (DEV):
APP_URL=http://localhost
FB_APP_ID=YOUR_APP_ID
FB_APP_SECRET=YOUR_APP_SECRET
FB_REDIRECT_URI=http://localhost/auth/facebook/callback
FB_GRAPH_VERSION=v19.0
FB_SSL_VERIFY=false
SESSION_DRIVER=file
SESSION_SECURE_COOKIE=false
SESSION_SAMESITE=lax
