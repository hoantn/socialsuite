<?php

namespace App\Services;

use Facebook\Facebook;
use Facebook\HttpClients\FacebookGuzzleHttpClient;
use GuzzleHttp\Client as GuzzleClient;

class FacebookClient
{
    protected Facebook $sdk;

    public function __construct()
    {
        $verify = filter_var(env('FB_SSL_VERIFY', 'true'), FILTER_VALIDATE_BOOLEAN);

        // Our dedicated Guzzle client (timeout + verify flag from .env)
        $guzzle = new GuzzleClient([
            'timeout' => (float) env('FB_HTTP_TIMEOUT', 30),
            'verify'  => $verify, // false in DEV on Windows/XAMPP to bypass local CA issues
        ]);

        // IMPORTANT: force SDK to use our Guzzle client
        $handler = new FacebookGuzzleHttpClient($guzzle);

        $this->sdk = new Facebook([
            'app_id'                => env('FB_APP_ID'),
            'app_secret'            => env('FB_APP_SECRET'),
            'default_graph_version' => env('FB_GRAPH_VERSION', 'v19.0'),

            // Make sure the SDK uses our handler (otherwise it may ignore our verify setting)
            'http_client_handler'   => $handler,

            // (optional) still pass the raw client for newer SDKs
            'http_client'           => $guzzle,
        ]);
    }

    public function sdk(): Facebook
    {
        return $this->sdk;
    }
}
