<?php
namespace App\Http\Controllers;
use App\Models\FacebookPage;
use App\Models\FacebookAccount;
use App\Models\PageMembership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class PageController extends Controller
{
  public function index(Request $r){
    $accountId = $r->session()->get('fb_account_id');
    $pages = FacebookPage::select('facebook_pages.*')
      ->join('page_memberships as m','m.facebook_page_id','=','facebook_pages.id')
      ->where('m.facebook_account_id',$accountId)->where('m.is_active',true)
      ->orderBy('name')->paginate(20);
    return view('pages.index', compact('pages'));
  }
  public function sync(Request $r){
    $account = FacebookAccount::find($r->session()->get('fb_account_id'));
    if (!$account) return back()->with('error','Chưa đăng nhập Facebook.');
    $resp = Http::get('https://graph.facebook.com/v19.0/me/accounts',[
      'fields'=>'id,name,access_token,perms,tasks','access_token'=>$account->user_access_token,'limit'=>100,
    ]);
    if (!$resp->ok()) return back()->with('error','Graph error: '.$resp->body());
    $added=0;
    foreach ((array)$resp->json('data') as $p){
      $page = \App\Models\FacebookPage::firstOrCreate(['page_id'=>$p['id']],['name'=>$p['name'] ?? 'Page']);
      PageMembership::updateOrCreate(
        ['facebook_account_id'=>$account->id,'facebook_page_id'=>$page->id],
        ['page_access_token'=>$p['access_token'] ?? null,'perms'=>$p['perms'] ?? $p['tasks'] ?? null,'is_active'=>true,'last_verified_at'=>now(),]
      );
      $added++;
    }
    return back()->with('ok',"Đã đồng bộ {$added} trang.");
  }
}