<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

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

    // Optional: block Facebook (ổn định, đúng cú pháp)
    'facebook' => [
        'client_id'     => env('FB_APP_ID'),
        'client_secret' => env('FB_APP_SECRET'),
        'redirect'      => env('FB_REDIRECT_URI'),
        'graph_version' => env('FB_GRAPH_VERSION', 'v19.0'),
        'webhook_verify_token' => env('FB_WEBHOOK_VERIFY', 'verify_token_dev'),
    ],

];
