<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\FacebookToken;

/**
 * [SOCIALSUITE][GPT][2025-10-18 08:45 +07]
 * CHANGE: Thêm flow OAuth "real app" cho Facebook, dùng manual token exchange (fallback)
 * WHY: Tránh phụ thuộc RedirectLoginHelper khi session/cookie có vấn đề; phù hợp production.
 * IMPACT: Endpoint /auth/facebook/login và /auth/facebook/callback
 * TEST: Thực hiện login -> callback -> lưu long-lived token -> test /me và /me/accounts
 * ROLLBACK: Xoá route + controller này; không đụng vendor SDK.
 */
class FacebookAuthController extends Controller
{
    public function login(Request $request)
    {
        $appId = config('services.facebook.client_id');
        $redirect = config('services.facebook.redirect');
        $scopes = ['public_profile','email','pages_show_list','pages_read_engagement','pages_manage_metadata'];
        $state = bin2hex(random_bytes(16));
        $request->session()->put('fb_oauth_state', $state);

        $authUrl = 'https://www.facebook.com/v19.0/dialog/oauth?' . http_build_query([
            'client_id'     => $appId,
            'redirect_uri'  => $redirect,
            'state'         => $state,
            'response_type' => 'code',
            'scope'         => implode(',', $scopes),
        ]);

        Log::info('[SOCIALSUITE][GPT] Redirect to FB OAuth', ['url' => $authUrl]);
        return redirect()->away($authUrl);
    }

    public function callback(Request $request)
    {
        $code = $request->query('code');
        $state = $request->query('state');
        $savedState = $request->session()->pull('fb_oauth_state');

        if (!$code) { return response('[SOCIALSUITE][GPT] Missing code in callback', 400); }
        if (!$state || !$savedState || !hash_equals($savedState, $state)) {
            Log::warning('[SOCIALSUITE][GPT] State mismatch', compact('state','savedState'));
        }

        $appId     = config('services.facebook.client_id');
        $appSecret = config('services.facebook.client_secret');
        $redirect  = config('services.facebook.redirect');

        // 1) code -> short-lived
        $tokenRes = Http::asForm()->get('https://graph.facebook.com/v19.0/oauth/access_token', [
            'client_id'     => $appId,
            'client_secret' => $appSecret,
            'redirect_uri'  => $redirect,
            'code'          => $code,
        ]);
        if (!$tokenRes->ok()) {
            Log::error('[SOCIALSUITE][GPT] Token exchange failed', ['body' => $tokenRes->body()]);
            return response('Token exchange failed: '.$tokenRes->body(), 500);
        }
        $short = $tokenRes->json();
        $shortToken = $short.get('access_token');

        // 2) short -> long-lived
        $llRes = Http::asForm()->get('https://graph.facebook.com/v19.0/oauth/access_token', [
            'grant_type'    => 'fb_exchange_token',
            'client_id'     => $appId,
            'client_secret' => $appSecret,
            'fb_exchange_token' => $shortToken,
        ]);
        if (!$llRes->ok()) {
            Log::error('[SOCIALSUITE][GPT] Long-lived exchange failed', ['body' => $llRes->body()]);
            return response('Long-lived exchange failed: '.$llRes->body(), 500);
        }
        $long = $llRes->json();
        $longToken = $long.get('access_token');
        $longExpiresIn = $long.get('expires_in');

        // 3) /me
        $meRes = Http::get('https://graph.facebook.com/v19.0/me', [
            'fields' => 'id,name',
            'access_token' => $longToken,
        ]);
        if (!$meRes->ok()) { return response('/me failed: '.$meRes->body(), 500); }
        $me = $meRes->json();
        $fbUserId = $me.get('id');
        $fbName = $me.get('name');

        $expiresAt = $longExpiresIn ? now()->addSeconds(intval($longExpiresIn)) : null;
        FacebookToken::updateOrCreate(['fb_user_id' => $fbUserId], [
            'user_id' => auth()->id(), 'token' => $longToken, 'expires_at' => $expiresAt, 'fb_name' => $fbName
        ]);

        // 4) /me/accounts
        $acRes = Http::get('https://graph.facebook.com/v19.0/me/accounts', [
            'fields' => 'id,name,access_token',
            'access_token' => $longToken,
        ]);
        $accounts = $acRes->json();

        return response()->json([
            'message' => '[SOCIALSUITE][GPT] OAuth OK',
            'fb_user' => $me,
            'expires_at' => optional($expiresAt)->toDateTimeString(),
            'accounts' => $accounts,
        ]);
    }
}
