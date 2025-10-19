<?php

return [
  'facebook' => [
    'client_id' => env('FB_CLIENT_ID',''),
    'client_secret' => env('FB_CLIENT_SECRET',''),
    'redirect' => env('FB_REDIRECT_URI', env('APP_URL').'/api/facebook/callback'),
  ],
];
