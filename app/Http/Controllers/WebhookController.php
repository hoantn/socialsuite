<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller {
    // Verification (GET)
    public function handleGet(Request $r) {
        $verify_token = config('services.facebook.webhook_verify_token', 'verify_token_dev');
        if ($r->get('hub_mode') === 'subscribe' && $r->get('hub_verify_token') === $verify_token) {
            return response($r->get('hub_challenge'), 200);
        }
        return response('Error, wrong validation token', 403);
    }

    // Receive (POST)
    public function handlePost(Request $r) {
        $payload = $r->all();
        Log::info('FB Webhook event', ['payload' => $payload]);
        // TODO: Dispatch jobs based on event type (comments/messages/etc).
        return response()->json(['ok' => true]);
    }
}
