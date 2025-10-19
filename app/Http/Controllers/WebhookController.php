<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request; use Illuminate\Support\Facades\Log;
class WebhookController extends Controller {
  public function handle(Request $request){
    if ($request->isMethod('get') && $request->has('hub_mode')) {
      if ($request->input('hub_verify_token') === env('META_VERIFY_TOKEN')) return response($request->input('hub_challenge'),200);
      return response('Invalid verify token',403);
    }
    Log::info('FB webhook', ['payload'=>$request->all()]); return response()->json(['ok'=>true]);
  }
}