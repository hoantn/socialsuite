<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\FacebookClient;
use App\Models\FbAccount;

class AuthController extends Controller
{
    // GET /auth/facebook/redirect
    public function redirect(FacebookClient $client)
    {
        // make sure session exists + new id to avoid fixation and stale state
        session()->start();
        session()->regenerate(true);

        $fb     = $client->sdk();
        $helper = $fb->getRedirectLoginHelper();

        $scopes = [
            'pages_show_list', 'pages_manage_metadata', 'pages_read_engagement',
            'pages_read_user_content', 'pages_manage_posts', 'pages_messaging'
        ];

        $loginUrl = $helper->getLoginUrl(config('facebook.redirect_uri'), $scopes);
        return redirect()->away($loginUrl);
    }

    // GET /auth/facebook/callback
    public function callback(Request $request, FacebookClient $client)
    {
        $fb     = $client->sdk();
        $helper = $fb->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken();
        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            Log::error('Graph returned an error', ['e' => $e->getMessage()]);
            abort(400, 'OAuth failed: '.$e->getMessage());
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            Log::error('Facebook SDK returned an error', ['e' => $e->getMessage()]);
            abort(400, 'OAuth failed: '.$e->getMessage());
        }

        if (!$accessToken) {
            abort(400, 'OAuth failed: no access token');
        }

        // Use token immediately
        $client->setDefaultAccessToken((string) $accessToken);

        // Fetch user profile
        $me = $fb->get('/me?fields=id,name,picture{url}')->getGraphUser();
        $fbUserId = (string) $me->getId();
        $name     = $me->getName();
        $avatar   = $me->getPicture() ? ($me->getPicture()->getUrl() ?? null) : null;

        // Save/Upsert
        $acc = \App\Models\FbAccount::updateOrCreate(
            ['fb_user_id' => $fbUserId],
            [
                'name'              => $name,
                'avatar_url'        => $avatar,
                'user_access_token' => (string) $accessToken,
            ]
        );

        // Keep account id in session for later pages sync
        session(['fb_account_id' => $acc->id]);

        return redirect('/pages');
    }
}
