<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Models\Page;
use App\Models\Webhook;

class FacebookAuthController extends Controller
{
    public function oauthUrl(Request $r){
        $state = Str::random(16);
        $appId = config('services.facebook.client_id','APP_ID');
        $redirect = urlencode(config('app.url').'/api/facebook/callback');
        $scope = urlencode('pages_show_list,pages_manage_metadata,pages_messaging');
        return ['url'=>"https://www.facebook.com/v21.0/dialog/oauth?client_id={$appId}&redirect_uri={$redirect}&state={$state}&scope={$scope}"];
    }
    public function callback(Request $r){
        Log::debug('fb.callback', ['code'=>$r->query('code'),'state'=>$r->query('state')]);
        return ['ok'=>true,'note'=>'Implement token exchange & page import here'];
    }
    public function listPages(){ return Page::orderByDesc('updated_at')->get(); }
    public function subscribeWebhook(Request $r, Page $page){
        $wh = Webhook::updateOrCreate(['page_id'=>$page->id,'topic'=>'messages'],['subscribed'=>true,'verify_token'=>Str::random(12)]);
        return ['ok'=>true,'webhook'=>$wh];
    }
}
