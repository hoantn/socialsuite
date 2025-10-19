<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\{Page,Subscriber,Conversation,Message};

class WebhookController extends Controller {
  public function handle(Request $request){
    // Verify
    if ($request->isMethod('get') && $request->has('hub_mode')) {
      if ($request->input('hub_verify_token') === env('META_VERIFY_TOKEN')) return response($request->input('hub_challenge'),200);
      return response('Invalid verify token',403);
    }

    $payload = $request->all();
    Log::info('FB webhook', ['payload'=>$payload]);

    if (($payload['object'] ?? '') === 'page') {
      foreach ($payload['entry'] ?? [] as $entry) {
        $pageId = $entry['id'] ?? null;
        $page = $pageId ? Page::where('page_id',$pageId)->first() : null;

        foreach ($entry['messaging'] ?? [] as $msg) {
          $psid = $msg['sender']['id'] ?? null;
          $text = $msg['message']['text'] ?? null;
          if (!$page || !$psid || !$text) continue;

          $sub = Subscriber::firstOrCreate(['page_id'=>$page->id, 'psid'=>$psid]);
          $conv = Conversation::firstOrCreate(['page_id'=>$page->id, 'subscriber_id'=>$sub->id], ['status'=>'bot']);
          Message::create(['conversation_id'=>$conv->id, 'direction'=>'inbound', 'text'=>$text, 'sent_at'=>now()]);
        }
      }
    }

    return response()->json(['ok'=>true]);
  }
}