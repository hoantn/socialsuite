<?php

return [
    'facebook' => [
        'graph_version' => env('FACEBOOK_GRAPH_VERSION', 'v20.0'),
        'app_id' => env('FACEBOOK_APP_ID', ''),
        'app_secret' => env('FACEBOOK_APP_SECRET', ''),
        'redirect_uri' => env('FACEBOOK_REDIRECT_URI', 'http://localhost/socialsuite/public/auth/facebook/callback'),
        // Phase 2: scopes sẽ dùng ở OAuth
        'scopes' => [
            'public_profile',
            'pages_show_list',
            'pages_read_engagement',
            'pages_manage_posts',
            'pages_manage_engagement',
            'pages_read_user_content',
            'business_management',
        ],
    ],
];
