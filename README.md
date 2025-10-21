# Patch: Force Facebook SDK to use our Guzzle client (2025_10_21_083039)

- Uses `Facebook\HttpClients\FacebookGuzzleHttpClient` with our `GuzzleHttp\Client`.
- Respects `.env` `FB_SSL_VERIFY=false` in DEV to bypass Windows CA issues.
- Also passes `'http_client' => $guzzle` for newer SDKs.

## .env
FB_SSL_VERIFY=false
FB_APP_ID=...
FB_APP_SECRET=...
FB_GRAPH_VERSION=v19.0

## After copying
php artisan optimize:clear
