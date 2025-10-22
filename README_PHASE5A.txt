PHASE 5A PATCH — Compose & Schedule multi-photo posts

Install:
1) Unzip into Laravel root.
2) In routes/web.php add: require base_path('routes/schedule.php');
3) Make sure models exist:
   App\Models\FbPage (page_id, name, user_id, access_token, picture_url, connected_ig_id, category)
   App\Models\ScheduledPost (fillable: page_id, message, media_paths, media_count, media_type, timezone, publish_at, status, batch_id, error)
4) Run queue worker: php artisan queue:work
5) Run dispatcher via cron each minute: * * * * * php /path/artisan socialsuite:dispatch-scheduled >>/dev/null 2>&1
