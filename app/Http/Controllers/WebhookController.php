<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Bus;
use App\Jobs\HandleMessageEventJob;
use App\Jobs\HandleCommentEventJob;

class WebhookController
{
    // GET verify: Facebook sends hub.mode, hub.verify_token, hub.challenge
    public function handleGet(Request $request)
    {
        $mode = $request->get('hub_mode') ?? $request->get('hub.mode');
        $token = $request->get('hub_verify_token') ?? $request->get('hub.verify_token');
        $challenge = $request->get('hub_challenge') ?? $request->get('hub.challenge');

        if ($mode === 'subscribe' && $token && hash_equals(env('FB_WEBHOOK_VERIFY_TOKEN', ''), $token)) {
            return response($challenge, 200);
        }
        return response('Forbidden', 403);
    }

    // POST receiver: enqueue jobs based on entry/messaging/changes
    public function handlePost(Request $request)
    {
        $payload = $request->all();
        Log::info('FB Webhook (raw)', ['body' => $payload]);

        // Basic guard
        if (!isset($payload['object']) || $payload['object'] !== 'page' || empty($payload['entry'])) {
            return response()->json(['ok' => true]);
        }

        foreach ($payload['entry'] as $entry) {
            // Messaging (inbox)
            if (!empty($entry['messaging'])) {
                foreach ($entry['messaging'] as $msg) {
                    // ignore delivery/read echoes
                    if (!empty($msg['message']) || !empty($msg['postback'])) {
                        Bus::dispatch(new HandleMessageEventJob($entry, $msg));
                    }
                }
            }

            // Feed / Comments
            if (!empty($entry['changes'])) {
                foreach ($entry['changes'] as $change) {
                    $field = $change['field'] ?? '';
                    if ($field === 'feed' && isset($change['value'])) {
                        Bus::dispatch(new HandleCommentEventJob($entry, $change));
                    }
                }
            }
        }

        return response()->json(['queued' => true]);
    }
}
