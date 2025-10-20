<?php
namespace App\Services;

use Facebook\Facebook;
use Carbon\Carbon;

class FacebookService {
    protected $fb;

    public function __construct() {
        $this->fb = new Facebook([
            'app_id' => env('FB_APP_ID'),
            'app_secret' => env('FB_APP_SECRET'),
            'default_graph_version' => 'v17.0',
        ]);
    }

    public function exchangeLongLivedToken($shortToken) {
        $o = $this->fb->getOAuth2Client();
        $token = $o->getLongLivedAccessToken($shortToken);
        return (string) $token;
    }

    public function getUserPages($userAccessToken) {
        $this->fb->setDefaultAccessToken($userAccessToken);
        $response = $this->fb->get('/me/accounts?fields=id,name,access_token,perms');
        return $response->getDecodedBody();
    }

    public function subscribePage($pageId, $pageAccessToken) {
        $this->fb->setDefaultAccessToken($pageAccessToken);
        $response = $this->fb->post("/{$pageId}/subscribed_apps", [
            'subscribed_fields' => 'messages,messaging_postbacks,messaging_referrals,feed'
        ]);
        return $response->getDecodedBody();
    }
}
