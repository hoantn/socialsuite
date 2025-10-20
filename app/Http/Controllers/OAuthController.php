<?php

namespace App\Http\Controllers;

use Facebook\Facebook;
use App\Models\FbAccount;
use Carbon\Carbon;

class AuthController extends Controller {
    public function redirect(Facebook $fb) {
        // 🔧 Đảm bảo PHP session đã mở trước khi gọi SDK
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session()->start();
        }

        $helper = $fb->getRedirectLoginHelper();
        $scopes = [
            'pages_show_list','pages_manage_metadata','pages_read_engagement',
            'pages_read_user_content','pages_manage_posts','pages_messaging'
        ];
        $loginUrl = $helper->getLoginUrl(env('FB_REDIRECT_URI'), $scopes);
        return redirect($loginUrl);
    }

    public function callback(Facebook $fb) {
        // 🔧 Mở session ở callback luôn (SDK đọc state từ session)
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session()->start();
        }

        $helper = $fb->getRedirectLoginHelper();
        $accessToken = $helper->getAccessToken();

        $oAuth2Client = $fb->getOAuth2Client();
        $longLived = $oAuth2Client->getLongLivedAccessToken($accessToken);

        $fb->setDefaultAccessToken($longLived);
        $res = $fb->get('/me?fields=id,name,picture');
        $me = $res->getGraphUser();

        $acc = FbAccount::updateOrCreate(
            ['fb_user_id' => (string)$me->getId()],
            [
                'name' => $me->getName(),
                'avatar_url' => $me->getPicture() ? $me->getPicture()->getUrl() : null,
                'user_access_token' => (string)$longLived,
                'token_expires_at' => Carbon::now()->addDays(55),
                'granted_scopes' => []
            ]
        );
        session(['fb_account_id' => $acc->id]);
        return redirect()->route('pages.index');
    }

    public function logout() {
        session()->forget('fb_account_id');
        return redirect()->route('home');
    }
}
