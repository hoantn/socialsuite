<?php

namespace App\Services;

use Facebook\Facebook;
use Facebook\HttpClients\FacebookCurlHttpClient;
use App\Support\Facebook\LaravelPersistentDataHandler;
use Illuminate\Contracts\Session\Session as SessionContract;

class FacebookClient
{
    protected Facebook $sdk;

    public function __construct(SessionContract $session)
    {
        $verify = filter_var(env('FB_SSL_VERIFY', 'true'), FILTER_VALIDATE_BOOLEAN);

        $this->sdk = new Facebook([
            'app_id'                  => config('facebook.app_id'),
            'app_secret'              => config('facebook.app_secret'),
            'default_graph_version'   => config('facebook.graph_version', 'v19.0'),
            'http_client_handler'     => new FacebookCurlHttpClient(),
            'curl_opts'               => [
                CURLOPT_SSL_VERIFYPEER => $verify,
                CURLOPT_CONNECTTIMEOUT => (int) env('FB_HTTP_TIMEOUT', 30),
                CURLOPT_TIMEOUT        => (int) env('FB_HTTP_TIMEOUT', 30),
            ],
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
