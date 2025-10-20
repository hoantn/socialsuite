<?php

namespace App\Services;

use Facebook\Facebook;
use Exception;

class FacebookClient {
    protected Facebook $fb;

    public function __construct() {
        $this->fb = new Facebook([
            'app_id' => config('services.facebook.client_id'),
            'app_secret' => config('services.facebook.client_secret'),
            'default_graph_version' => config('services.facebook.graph_version', 'v19.0'),
        ]);
    }

    public function sdk(): Facebook { return $this->fb; }

    public function withPageToken(string $pageToken): void {
        $this->fb->setDefaultAccessToken($pageToken);
    }
}
