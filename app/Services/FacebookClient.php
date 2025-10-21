<?php

namespace App\Services;

use Facebook\Facebook;
use GuzzleHttp\Client as GuzzleClient;
use App\Support\Facebook\LaravelPersistentDataHandler;
use Illuminate\Contracts\Session\Session as SessionContract;

class FacebookClient
{
    protected Facebook $sdk;

    public function __construct(SessionContract $session)
    {
        $verify = filter_var(env('FB_SSL_VERIFY', 'true'), FILTER_VALIDATE_BOOLEAN);

        $guzzle = new GuzzleClient([
            'timeout' => (float) env('FB_HTTP_TIMEOUT', 30),
            'verify'  => $verify,
        ]);

        // Use SDK's built-in 'guzzle' handler for best compatibility with Guzzle 6/7
        $this->sdk = new Facebook([
            'app_id'                  => config('facebook.app_id'),
            'app_secret'              => config('facebook.app_secret'),
            'default_graph_version'   => config('facebook.graph_version', 'v19.0'),
            'http_client_handler'     => 'guzzle',
            'http_client'             => $guzzle,
            'persistent_data_handler' => new LaravelPersistentDataHandler($session),
        ]);
    }

    public function sdk(): Facebook
    {
        return $this->sdk;
    }

    public function setDefaultAccessToken(?string $token): void
    {
        if ($token) {
            $this->sdk->setDefaultAccessToken($token);
        }
    }
}
