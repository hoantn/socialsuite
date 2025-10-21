<?php

namespace App\Services;

use Facebook\Facebook;
use App\Support\Facebook\LaravelPersistentDataHandler;

// Conditionally use Guzzle if installed
if (class_exists(\GuzzleHttp\Client::class)) {
    class_alias(\GuzzleHttp\Client::class, 'GuzzleClientClass');
}

class FacebookClient {
    protected Facebook $fb;

    public function __construct() {
        $config = [
            'app_id'  => env('FB_APP_ID', env('FACEBOOK_APP_ID')),
            'app_secret' => env('FB_APP_SECRET', env('FACEBOOK_APP_SECRET')),
            'default_graph_version' => env('FB_GRAPH_VERSION','v19.0'),
            'persistent_data_handler' => new LaravelPersistentDataHandler(app('session')),
        ];

        if (class_exists('GuzzleClientClass')) {
            $verify = true;
            $env = strtolower((string) env('FB_SSL_VERIFY', 'false'));
            if ($env === 'false' || $env === '0') {
                $verify = false; // DEV: disable SSL verify
            } else {
                $path = env('FB_CACERT_PATH');
                $verify = $path ? $path : true;
            }
            $httpClient = new \GuzzleClientClass(['verify' => $verify, 'timeout' => 30]);
            $config['http_client'] = $httpClient;
        }

        $this->fb = new Facebook($config);
    }

    public function sdk(): Facebook { return $this->fb; }

    public function withPageToken(string $pageToken): void {
        $this->fb->setDefaultAccessToken($pageToken);
    }
}
