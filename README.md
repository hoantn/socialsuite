# Patch: fix SSL error in Pages sync (2025_10_21_082406)

- This PagesController uses the injected `App\Services\FacebookClient`, which
  in DEV reads `FB_SSL_VERIFY=false` to disable certificate verification so you
  won't see `SSL certificate problem: unable to get local issuer certificate`.

## Required .env for DEV
FB_SSL_VERIFY=false
FB_APP_ID=...
FB_APP_SECRET=...
FB_GRAPH_VERSION=v19.0

## After copying
php artisan optimize:clear
