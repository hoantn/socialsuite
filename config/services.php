<?php

return [

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    // Optional – không bắt buộc, nhưng để đây cho đồng bộ
    'facebook' => [
        'client_id'     => env('FB_APP_ID', env('FACEBOOK_APP_ID')),
        'client_secret' => env('FB_APP_SECRET', env('FACEBOOK_APP_SECRET')),
        'redirect'      => env('FB_REDIRECT_URI', env('FACEBOOK_REDIRECT_URI')),
        'graph_version' => env('FB_GRAPH_VERSION', 'v19.0'),
        'webhook_verify_token' => env('FB_WEBHOOK_VERIFY', 'verify_token_dev'),
    ],

];
