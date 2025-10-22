<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\FbUser;
use App\Models\FbPage;
use App\Models\FbPageToken;

class FacebookManualController extends Controller
{
    protected function graphV(): string
    {
        return config('socialsuite.facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v20.0'));
    }

    public function home()
    {
        return view('welcome');
    }

    public function redirect(Request $request)
    {
        $state = Str::random(32);
        session(['fb_oauth_state' => $state]);

        $clientId = env('FACEBOOK_CLIENT_ID');
        $redirectUri = env('FACEBOOK_REDIRECT_URI', url('/auth/facebook/callback'));

        $scopes = config('socialsuite.facebook.scopes', [
            'public_profile',
            'pages_show_list',
            'pages_read_engagement',
            'pages_manage_posts',
            'pages_manage_engagement',
            'pages_read_user_content',
            'business_management',
        ]);

        $authUrl = 'https://www.facebook.com/'.$this->graphV().'/dialog/oauth?' . http_build_query([
            'client_id'     => $clientId,
            'redirect_uri'  => $redirectUri,
            'state'         => $state,
            'response_type' => 'code',
            'scope'         => implode(',', $scopes),
        ]);

        return redirect()->away($authUrl);
    }

    public function callback(Request $request)
    {
        $state = $request->query('state');
        if (!$state || $state !== session('fb_oauth_state')) {
            return redirect()->route('home')->with('status', 'Sai hoặc thiếu state (CSRF).');
        }
        session()->forget('fb_oauth_state');

        $code = $request->query('code');
        if (!$code) {
            return redirect()->route('home')->with('status', 'Thiếu code từ Facebook.');
        }

        $clientId = env('FACEBOOK_CLIENT_ID');
        $clientSecret = env('FACEBOOK_CLIENT_SECRET');
        $redirectUri = env('FACEBOOK_REDIRECT_URI', url('/auth/facebook/callback'));

        // Exchange code -> short-lived token
        $tokenResp = Http::asForm()->get('https://graph.facebook.com/'.$this->graphV().'/oauth/access_token', [
            'client_id'     => $clientId,
            'client_secret' => $clientSecret,
            'redirect_uri'  => $redirectUri,
            'code'          => $code,
        ]);

        if (!$tokenResp->ok()) {
            Log::error('OAuth token exchange fail', ['body' => $tokenResp->body()]);
            return redirect()->route('home')->with('status', 'Đổi token thất bại.');
        }

        $short = $tokenResp->json();
        $shortToken = $short['access_token'] ?? null;
        if (!$shortToken) {
            return redirect()->route('home')->with('status', 'Không nhận được access token.');
        }

        // Exchange to long-lived token
        $longResp = Http::asForm()->get('https://graph.facebook.com/'.$this->graphV().'/oauth/access_token', [
            'grant_type'        => 'fb_exchange_token',
            'client_id'         => $clientId,
            'client_secret'     => $clientSecret,
            'fb_exchange_token' => $shortToken,
        ]);

        $long = $longResp->ok() ? $longResp->json() : [];
        $accessToken = $long['access_token'] ?? $shortToken;
        $expires = isset($long['expires_in']) ? now()->addSeconds($long['expires_in']) : null;

        // Fetch profile
        $me = Http::get('https://graph.facebook.com/'.$this->graphV().'/me', [
            'fields'       => 'id,name,email,picture{url}',
            'access_token' => $accessToken,
        ])->json();

        // Save user
        $user = FbUser::updateOrCreate(
            ['fb_user_id' => $me['id'] ?? ''],
            [
                'name' => $me['name'] ?? null,
                'email' => $me['email'] ?? null,
                'picture_url' => $me['picture']['data']['url'] ?? null,
                'access_token' => $accessToken,
                'access_token_expires_at' => $expires,
                'raw' => $me,
            ]
        );

        // Sync pages
        $this->syncPages($user);

        session(['fb_uid' => $user->id]);
        return redirect()->route('dashboard');
    }

    protected function syncPages(FbUser $user): void
    {
        $fields = 'id,name,category,picture{url},connected_instagram_account,access_token';
        $resp = Http::get('https://graph.facebook.com/'.$this->graphV().'/me/accounts', [
            'fields'       => $fields,
            'access_token' => $user->access_token,
            'limit'        => 200,
        ]);

        if (!$resp->ok()) {
            Log::error('Fetch pages fail', ['body' => $resp->body()]);
            return;
        }

        foreach (($resp->json()['data'] ?? []) as $p) {
            $pageId = $p['id'];
            FbPage::updateOrCreate(
                ['page_id' => $pageId],
                [
                    'owner_id' => $user->id,
                    'name' => $p['name'] ?? null,
                    'category' => $p['category'] ?? null,
                    'picture_url' => $p['picture']['data']['url'] ?? null,
                    'connected_ig_id' => $p['connected_instagram_account'] ?? null,
                    'raw' => $p,
                ]
            );

            FbPageToken::updateOrCreate(
                ['page_id' => $pageId],
                [
                    'access_token' => $p['access_token'] ?? null,
                    'access_token_expires_at' => null,
                    'raw' => $p,
                ]
            );
        }
    }

    public function dashboard()
    {
        $uid = session('fb_uid');
        $user = $uid ? FbUser::find($uid) : null;
        $pages = $user ? FbPage::where('owner_id', $user->id)->orderBy('name')->get() : collect();

        return view('dashboard', compact('user', 'pages'));
    }

    public function logout()
    {
        session()->forget('fb_uid');
        return redirect()->route('home');
    }
}
