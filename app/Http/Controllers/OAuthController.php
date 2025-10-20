<?php

namespace App\Http\Controllers;

use Facebook\Facebook;
use Facebook\PersistentData\FacebookSessionPersistentDataHandler;
use App\Models\FbAccount;
use Carbon\Carbon;

class AuthController extends Controller
{
    /** Đảm bảo PHP session mở trước khi gọi SDK */
    private function ensurePhpSession(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            // Dùng Laravel session nếu có, rồi mở PHP session nền
            try { session()->start(); } catch (\Throwable $e) {}
            if (session_status() !== PHP_SESSION_ACTIVE) {
                @session_start();
            }
        }
    }

    public function redirect(Facebook $fb)
    {
        $this->ensurePhpSession();

        // Chỉ định handler sử dụng PHP session cho SDK (bắt buộc cho OAuth)
        $fb->setPersistentDataHandler(new FacebookSessionPersistentDataHandler());

        $helper = $fb->getRedirectLoginHelper();

        $scopes = [
            'pages_show_list','pages_manage_metadata','pages_read_engagement',
            'pages_read_user_content','pages_manage_posts','pages_messaging'
        ];

        // Đọc từ ENV (đã set đúng)
        $redirect = env('FB_REDIRECT_URI');
        return redirect($helper->getLoginUrl($redirect, $scopes));
    }

    public function callback(Facebook $fb)
    {
        $this->ensurePhpSession();
        $fb->setPersistentDataHandler(new FacebookSessionPersistentDataHandler());

        $helper = $fb->getRedirectLoginHelper();
        $accessToken = $helper->getAccessToken();         // có thể null nếu người dùng bấm Cancel
        if (!$accessToken) {
            // cố gắng lấy từ lỗi trả về
            $error = $helper->getError();
            abort(400, 'OAuth failed: '.($error ?? 'unknown'));
        }

        // Đổi sang long-lived token
        $oAuth2Client = $fb->getOAuth2Client();
        $longLived = $oAuth2Client->getLongLivedAccessToken($accessToken);

        // Test gọi /me
        $fb->setDefaultAccessToken($longLived);
        $me = $fb->get('/me?fields=id,name,picture')->getGraphUser();

        // Lưu account + token
        $acc = \App\Models\FbAccount::updateOrCreate(
            ['fb_user_id' => (string) $me->getId()],
            [
                'name' => $me->getName(),
                'avatar_url' => $me->getPicture() ? $me->getPicture()->getUrl() : null,
                'user_access_token' => (string) $longLived,
                'token_expires_at' => Carbon::now()->addDays(55),
                'granted_scopes' => [],
            ]
        );

        session(['fb_account_id' => $acc->id]);
        return redirect()->route('pages.index');
    }

    public function logout()
    {
        session()->forget('fb_account_id');
        return redirect()->route('home');
    }
}
