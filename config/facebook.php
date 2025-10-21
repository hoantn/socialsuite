<?php

return [
    'app_id'        => env('FB_APP_ID'),
    'app_secret'    => env('FB_APP_SECRET'),
    'graph_version' => env('FB_GRAPH_VERSION', 'v19.0'),
    'redirect_uri'  => rtrim(env('FB_REDIRECT_URI', rtrim(env('APP_URL'), '/').'/auth/facebook/callback'), '/'),
];
