<?php

namespace App\Services;

use Facebook\Facebook;
use Facebook\HttpClients\FacebookGuzzleHttpClient;
use App\Support\Facebook\LaravelPersistentDataHandler;
use GuzzleHttp\Client as GuzzleClient;

class FacebookClient {
    protected Facebook $fb;

    public function __construct() {
        $verify = true;
        $env = strtolower((string) env('FB_SSL_VERIFY', 'false'));
        if ($env === 'false' || $env === '0') {
            $verify = false;
        } else {
            $path = env('FB_CACERT_PATH');
            $verify = $path ? $path : true;
        }

        $guzzle = new GuzzleClient([
            'verify'  => $verify,
            'timeout' => 30,
        ]);

        $handler = new FacebookGuzzleHttpClient($guzzle);

        $this->fb = new Facebook([
            'app_id'  => env('FB_APP_ID', env('FACEBOOK_APP_ID')),
            'app_secret' => env('FB_APP_SECRET', env('FACEBOOK_APP_SECRET')),
            'default_graph_version' => env('FB_GRAPH_VERSION','v19.0'),
            'persistent_data_handler' => new LaravelPersistentDataHandler(app('session')),
            'http_client_handler' => $handler,
        ]);
    }

    public function sdk(): Facebook { return $this->fb; }

    public function withPageToken(string $pageToken): void {
        $this->fb->setDefaultAccessToken($pageToken);
    }
}
