<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Facebook\Facebook;
use App\Models\Page;
class FacebookController extends Controller
{
    public function redirect(){
        return Socialite::driver('facebook')
            ->scopes(['pages_manage_posts','pages_read_engagement','pages_manage_engagement','pages_manage_metadata','pages_show_list'])
            ->redirect();
    }
    public function callback(){
        $fbUser = Socialite::driver('facebook')->user();
        session(['fb_user_token'=>$fbUser->token]);
        $fb = new Facebook(['app_id'=>config('services.facebook.client_id'),
            'app_secret'=>config('services.facebook.client_secret'),'default_graph_version'=>'v19.0']);
        $response = $fb->get('/me/accounts', $fbUser->token);
        $pages = json_decode($response->getBody(), true)['data'] ?? [];
        $user = Auth::user();
        foreach($pages as $p){
            Page::updateOrCreate(['user_id'=>$user->id,'page_id'=>$p['id']],
                ['name'=>$p['name']??'Page','page_token'=>$p['access_token']??'']);
        }
        return redirect()->route('fb.pages');
    }
    public function pages(){
        $pages = Page::where('user_id',Auth::id())->get();
        return view('facebook.pages', compact('pages'));
    }
    public function selectPage(Request $r){
        $r->validate(['page_id'=>'required']); session(['active_page_id'=>$r->page_id]);
        return back()->with('ok','Đã chọn Page.');
    }
}
