
<?php

namespace App\Http\Controllers;

use App\Models\SocialAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller
{
    public function redirect()
    {
        $scopes = ['email','pages_show_list','pages_manage_metadata','pages_messaging'];

        return Socialite::driver('facebook')
            ->scopes($scopes)
            ->redirect();
    }

    public function callback()
    {
        $fbUser = Socialite::driver('facebook')->user();

        $user = Auth::user() ?? \App\Models\User::first();
        if (!$user) {
            abort(401, 'No user available to attach SocialAccount. Please create a user or implement Auth.');
        }

        SocialAccount::updateOrCreate(
            ['provider' => 'facebook', 'provider_user_id' => (string)$fbUser->getId()],
            [
                'user_id'          => $user->id,
                'access_token'     => $fbUser->token,
                'refresh_token'    => $fbUser->refreshToken ?? null,
                'token_expires_at' => isset($fbUser->expiresIn) ? now()->addSeconds($fbUser->expiresIn) : null,
                'raw'              => $fbUser->user ?? null,
            ]
        );

        return redirect()->route('spa')->with('connected', 'facebook');
    }
}
