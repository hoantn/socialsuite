<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Inertia\Inertia;
use App\Services\MetaGraph;
use App\Models\Page;

class OAuthController extends Controller {
  public function redirect() {
    return Socialite::driver('facebook')->scopes(['pages_show_list','pages_messaging','pages_manage_metadata'])->redirect();
  }

  public function callback(MetaGraph $graph) {
    $user = Socialite::driver('facebook')->user();
    session(['fb_user_token' => $user->token]);
    $accounts = $graph->meAccounts($user->token);
    $list = $accounts['data'] ?? [];
    return Inertia::render('Pages/Connect', ['accounts' => $list]);
  }

  public function importPages(Request $r, MetaGraph $graph) {
    $data = $r->validate(['pages'=>'required|array']);
    foreach($data['pages'] as $p) {
      $page = Page::updateOrCreate(
        ['page_id' => $p['id']],
        ['user_id'=>1,'channel'=>'messenger','name'=>$p['name']??('Page '.$p['id']),'access_token'=>$p['access_token']??null,'perms'=>$p['perms']??[]]
      );
      if ($page->access_token) {
        $ok = $graph->subscribeApp($page->access_token);
        if ($ok) $page->update(['subscribed'=>true]);
      }
    }
    return redirect()->route('pages.index')->with('success','Đã nhập & subscribe Webhook cho các Page.');
  }
}