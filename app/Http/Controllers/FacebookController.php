<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Jobs\SendFacebookMessage;
use App\Jobs\BroadcastCampaign;
use App\Models\{Page,Campaign};

class FacebookController extends Controller {
  public function send(Request $r) {
    $data = $r->validate(['page_id'=>'required|int','psid'=>'required','text'=>'required']);
    $page = Page::findOrFail($data['page_id']);
    if (!$page->access_token) return response()->json(['error'=>'Page token missing'], 422);
    SendFacebookMessage::dispatch($page->access_token, $data['psid'], $data['text']);
    return response()->json(['queued'=>true]);
  }

  public function broadcast(Request $r) {
    $d = $r->validate(['page_id'=>'required|int','name'=>'required','content'=>'required']);
    $camp = Campaign::create($d + ['status'=>'queued']);
    BroadcastCampaign::dispatch($camp->id);
    return response()->json(['queued'=>true,'campaign_id'=>$camp->id]);
  }
}