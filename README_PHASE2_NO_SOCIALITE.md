# SocialSuite — Phase 2 (No Socialite)
Pure Facebook OAuth using Graph API + Laravel HTTP client (no external OAuth packages).

## What this adds
- OAuth flow without Socialite: build the dialog URL, verify state, exchange `code` for token.
- Exchange **long‑lived token**, fetch profile, sync Pages (+ page tokens).
- Dashboard shows Pages.

## Why this approach
Composer reported version conflicts for Socialite; this build **removes the dependency** entirely.
It works on Laravel 12 out-of-the-box.

## Setup
1) Extract this ZIP over your existing project.
2) Set `.env`:
```
APP_URL=http://localhost

FACEBOOK_CLIENT_ID=your_app_id
FACEBOOK_CLIENT_SECRET=your_app_secret
FACEBOOK_REDIRECT_URI=http://localhost/auth/facebook/callback
FACEBOOK_GRAPH_VERSION=v20.0
```
3) On Facebook Developer Console → add **Valid OAuth Redirect URIs**:
   `http://localhost/auth/facebook/callback`
4) Clear caches:
```
php artisan config:clear
php artisan route:clear
```
5) Visit `http://localhost` → Đăng nhập Facebook.

— Generated 2025-10-22
