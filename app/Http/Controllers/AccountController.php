<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\FacebookAccount;
use App\Models\FacebookPage;
use App\Models\PageMembership;

class AccountController extends Controller
{
    public function me(Request $r){
        $account = FacebookAccount::findOrFail($r->session()->get('fb_account_id'));
        $pagesCount = PageMembership::where('facebook_account_id',$account->id)->count();
        return view('account/me', compact('account','pagesCount'));
    }
    public function syncPages(Request $r){
        $account = FacebookAccount::findOrFail($r->session()->get('fb_account_id'));
        $resp = Http::get('https://graph.facebook.com/v19.0/me/accounts',[
            'fields'=>'id,name,access_token,perms,tasks',
            'access_token'=>$account->user_access_token, 'limit'=>100
        ]);
        if (!$resp->ok()) return back()->with('error','Graph error: '.$resp->body());
        $added=0;
        foreach ((array)$resp->json('data') as $p) {
            $page = FacebookPage::firstOrCreate(['page_id'=>$p['id']], ['name'=>$p['name'] ?? 'Page']);
            PageMembership::updateOrCreate(
                ['facebook_account_id'=>$account->id,'facebook_page_id'=>$page->id],
                ['page_access_token'=>$p['access_token'] ?? null,'perms'=>$p['perms'] ?? ($p['tasks'] ?? null),'is_active'=>true,'last_verified_at'=>now()]
            );
            $added++;
        }
        return redirect()->route('pages')->with('ok',"Đã đồng bộ {$added} trang.");
    }
}
