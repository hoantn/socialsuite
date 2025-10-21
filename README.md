
# SocialSuite – Next Steps (Webhook + Jobs + Subscribe) – 2025_10_21_075855

This bundle adds:
- Robust Facebook Page Webhook handler (GET verify + POST events)
- Queue jobs for messages and comments
- A small service wrapper for Graph calls
- `subscribe` endpoint implementation to subscribe pages to Webhooks
- Database queue migration (jobs table)

## Files
- app/Http/Controllers/WebhookController.php
- app/Http/Controllers/Api/FacebookController.php  (updated subscribe logic)
- app/Jobs/HandleMessageEventJob.php
- app/Jobs/HandleCommentEventJob.php
- app/Services/FacebookActions.php
- database/migrations/2025_10_21_000001_create_jobs_table.php
- routes/web.php (ONLY instructions comment – keep your existing routes if already set)

## .env additions
```
FB_WEBHOOK_VERIFY_TOKEN=dev-verify-token
QUEUE_CONNECTION=database
```

## Run
```
php artisan migrate
php artisan queue:work
```

This assumes you already have:
- Page records with page access tokens (`account_page` table)
- Webhook routes: 
  - GET  /webhooks/facebook
  - POST /webhooks/facebook
If not, see the comment at the bottom of this README for route snippets.
