# Patch 2 — Proper Guzzle wiring for Facebook SDK (2025_10_21_064930)

Replace `app/Services/FacebookClient.php` with the file in this zip.
It injects Guzzle using **http_client_handler**, which the SDK actually uses.
Then `.env` flags work:

- `FB_SSL_VERIFY=false`  -> disable SSL verify in DEV
- `FB_CACERT_PATH=...`   -> custom CA for verify=true

Commands:
    composer require guzzlehttp/guzzle:^7
    php artisan optimize:clear
