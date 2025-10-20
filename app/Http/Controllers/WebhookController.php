<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller {
    public function handleGet(Request $r) {
        $verify = env('FB_WEBHOOK_VERIFY','verify_token_dev');
        if ($r->get('hub_mode') === 'subscribe' && $r->get('hub_verify_token') === $verify) {
            return response($r->get('hub_challenge'), 200);
        }
        return response('Error, wrong validation token', 403);
    }
    public function handlePost(Request $r) {
        Log::info('FB Webhook', ['payload'=>$r->all()]);
        return response()->json(['ok'=>true]);
    }
}
