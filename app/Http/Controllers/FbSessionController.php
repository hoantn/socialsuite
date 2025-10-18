<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\FacebookAccount;
class FbSessionController extends Controller
{
  public function bind(Request $r){
    $token=$r->input('access_token'); if(!$token) abort(400,'missing token');
    $me=Http::get('https://graph.facebook.com/v19.0/me',['fields'=>'id,name,picture.type(large)','access_token'=>$token])->json();
    $acc=FacebookAccount::updateOrCreate(['fb_user_id'=>$me['id'] ?? ''],[
      'name'=>$me['name'] ?? null,'avatar_url'=>$me['picture']['data']['url'] ?? null,'user_access_token'=>$token,'expires_at'=>now()->addDays(60),
    ]);
    $r->session()->put('fb_account_id',$acc->id);
    return redirect('/pages');
  }
  public function logout(Request $r){ $r->session()->forget('fb_account_id'); return redirect('/'); }
}