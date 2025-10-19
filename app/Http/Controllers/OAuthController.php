
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Http;
use App\Models\FacebookUser;

class OAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('facebook')
            ->scopes(['pages_show_list','pages_manage_metadata','pages_messaging','public_profile','email'])
            ->redirect();
    }

    public function callback(Request $request)
    {
        $fbUser = Socialite::driver('facebook')->stateless()->user();

        $token = $fbUser->token;
        $res = Http::get('https://graph.facebook.com/oauth/access_token', [
            'grant_type'        => 'fb_exchange_token',
            'client_id'         => config('services.facebook.client_id'),
            'client_secret'     => config('services.facebook.client_secret'),
            'fb_exchange_token' => $token,
        ])->json();

        $longLived = $res['access_token'] ?? $token;

        FacebookUser::updateOrCreate(
            ['user_id' => auth()->id() ?? 0, 'fb_user_id' => $fbUser->getId()],
            ['access_token' => $longLived, 'profile' => $fbUser->user]
        );

        return redirect('/pages');
    }
}
