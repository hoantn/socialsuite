<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\FbUser;
use App\Models\FbPage;
use App\Models\FbPageToken;
use Carbon\Carbon;

class FacebookController extends Controller
{
    public function home()
    {
        return view('welcome');
    }

    public function redirect()
    {
        $scopes = config('socialsuite.facebook.scopes', [
            'public_profile',
            'pages_show_list',
            'pages_read_engagement',
            'pages_manage_posts',
            'pages_manage_engagement',
            'pages_read_user_content',
            'business_management',
        ]);

        return Socialite::driver('facebook')
            ->scopes($scopes)
            ->redirect();
    }

    public function callback(Request $request)
    {
        try {
            $fbUser = Socialite::driver('facebook')->stateless()->user();
        } catch (\Throwable $e) {
            Log::error('FB OAuth error: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->route('home')->with('status', 'OAuth lỗi: '.$e->getMessage());
        }

        // Đổi sang long-lived token
        $graphVersion = config('socialsuite.facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v20.0'));
        $appId = env('FACEBOOK_CLIENT_ID');
        $appSecret = env('FACEBOOK_CLIENT_SECRET');

        $exchangeUrl = "https://graph.facebook.com/{$graphVersion}/oauth/access_token";
        $resp = Http::asForm()->get($exchangeUrl, [
            'grant_type' => 'fb_exchange_token',
            'client_id' => $appId,
            'client_secret' => $appSecret,
            'fb_exchange_token' => $fbUser->token,
        ]);

        if (!$resp->ok()) {
            Log::warning('Long-lived exchange fail', ['body' => $resp->body()]);
            $longLivedToken = $fbUser->token;
            $expiresAt = null;
        } else {
            $data = $resp->json();
            $longLivedToken = $data['access_token'] ?? $fbUser->token;
            $expiresAt = isset($data['expires_in']) ? now()->addSeconds($data['expires_in']) : null;
        }

        // Lưu user
        $user = FbUser::updateOrCreate(
            ['fb_user_id' => $fbUser->id],
            [
                'name' => $fbUser->name ?? null,
                'email' => $fbUser->email ?? null,
                'picture_url' => $fbUser->avatar ?? null,
                'access_token' => $longLivedToken,
                'access_token_expires_at' => $expiresAt,
                'raw' => $fbUser,
            ]
        );

        // Đồng bộ pages
        $this->syncPages($user);

        // Lưu user_id vào session
        session(['fb_uid' => $user->id]);

        return redirect()->route('dashboard');
    }

    protected function syncPages(FbUser $user): void
    {
        $graphVersion = config('socialsuite.facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v20.0'));

        $fields = 'id,name,category,picture{{url}},connected_instagram_account,access_token';
        $url = "https://graph.facebook.com/{$graphVersion}/me/accounts";
        $resp = Http::get($url, [
            'fields' => $fields,
            'access_token' => $user->access_token,
            'limit' => 200,
        ]);

        if (!$resp->ok()) {
            Log::error('Fetch pages fail', ['body' => $resp->body()]);
            return;
        }

        $data = $resp->json();
        $pages = $data['data'] ?? [];

        foreach ($pages as $p) {
            $pageId = $p['id'];
            $page = FbPage::updateOrCreate(
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

            // token page
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

    public function dashboard(Request $request)
    {
        $uid = session('fb_uid');
        $user = $uid ? FbUser::find($uid) : null;
        $pages = $user ? FbPage::where('owner_id', $user->id)->orderBy('name')->get() : collect();

        return view('dashboard', [
            'user' => $user,
            'pages' => $pages,
        ]);
    }

    public function logout()
    {
        session()->forget('fb_uid');
        return redirect()->route('home');
    }
}
