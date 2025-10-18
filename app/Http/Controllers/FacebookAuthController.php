<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\FacebookToken;
use App\Models\FacebookPage;
/** [SOCIALSUITE][GPT] Facebook OAuth + save Page tokens */
class FacebookAuthController extends Controller {
    public function login(Request $r) {
        $appId = config('services.facebook.client_id');
        $redirect = config('services.facebook.redirect');
        $scopes = ['public_profile','email','pages_show_list','pages_read_engagement','pages_manage_metadata'];
        $state = bin2hex(random_bytes(16));
        $r->session()->put('fb_oauth_state', $state);
        $url = 'https://www.facebook.com/v19.0/dialog/oauth?' . http_build_query([
            'client_id'=>$appId,'redirect_uri'=>$redirect,'state'=>$state,'response_type'=>'code','scope'=>implode(',', $scopes),
        ]);
        return redirect()->away($url);
    }
    public function callback(Request $r) {
        $code = $r->query('code'); if (!$code) return response('Missing code',400);
        $appId = config('services.facebook.client_id');
        $appSecret = config('services.facebook.client_secret');
        $redirect = config('services.facebook.redirect');
        $t1 = Http::asForm()->get('https://graph.facebook.com/v19.0/oauth/access_token',[
            'client_id'=>$appId,'client_secret'=>$appSecret,'redirect_uri'=>$redirect,'code'=>$code,
        ]); if (!$t1->ok()) return response('Token exchange failed: '.$t1->body(),500);
        $short = $t1->json('access_token');
        $t2 = Http::asForm()->get('https://graph.facebook.com/v19.0/oauth/access_token',[
            'grant_type'=>'fb_exchange_token','client_id'=>$appId,'client_secret'=>$appSecret,'fb_exchange_token'=>$short,
        ]); if (!$t2->ok()) return response('Long-lived exchange failed: '.$t2->body(),500);
        $long = $t2->json('access_token'); $exp = $t2->json('expires_in');
        $me = Http::get('https://graph.facebook.com/v19.0/me',['fields'=>'id,name','access_token'=>$long]);
        if (!$me->ok()) return response('/me failed: '.$me->body(),500);
        $meData = $me->json();
        $expiresAt = $exp ? now()->addSeconds((int)$exp) : null;
        FacebookToken::updateOrCreate(['fb_user_id'=>$meData['id']], [
            'user_id'=>auth()->id(),'fb_name'=>$meData['name']??null,'token'=>$long,'expires_at'=>$expiresAt,
        ]);
        $pagesRes = Http::get('https://graph.facebook.com/v19.0/me/accounts', [
            'fields'=>'id,name,access_token','access_token'=>$long,
        ]);
        $saved = [];
        if ($pagesRes->ok()) {
            $pages = $pagesRes->json('data') ?? [];
            foreach ($pages as $p) {
                $pageId = $p['id'] ?? null;
                $pageName = $p['name'] ?? null;
                $pageToken = $p['access_token'] ?? '';
                if ($pageId) {
                    $rec = FacebookPage::updateOrCreate(
                        ['fb_user_id'=>$meData['id'],'page_id'=>$pageId],
                        ['user_id'=>auth()->id(),'name'=>$pageName,'access_token'=>$pageToken]
                    );
                    $saved[] = ['page_id'=>$rec->page_id,'name'=>$rec->name];
                }
            }
        }
        return response()->json([
            'message'=>'OAuth OK','fb_user'=>$meData,'expires_at'=>optional($expiresAt)->toDateTimeString(),'pages_saved'=>$saved,
        ]);
    }
}
