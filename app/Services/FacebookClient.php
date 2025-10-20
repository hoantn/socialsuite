<?php

namespace App\Services;

use Facebook\Facebook;

class FacebookClient {
    protected Facebook $fb;

    public function __construct() {
        $this->fb = new \Facebook\Facebook([
			'app_id' => env('FB_APP_ID', env('FACEBOOK_APP_ID')),
			'app_secret' => env('FB_APP_SECRET', env('FACEBOOK_APP_SECRET')),
			'default_graph_version' => env('FB_GRAPH_VERSION', 'v19.0'),
		]);
    }
    public function sdk(): Facebook { return $this->fb; }
    public function withPageToken(string $pageToken): void { $this->fb->setDefaultAccessToken($pageToken); }
}
