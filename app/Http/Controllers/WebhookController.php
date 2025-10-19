
<?php

namespace App\Http\Controllers;

use App\Models\WebhookLog;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function handle(Request $req)
    {
        if ($req->isMethod('get')) {
            $mode = $req->query('hub_mode') ?: $req->query('hub.mode');
            $token = $req->query('hub_verify_token') ?: $req->query('hub.verify_token');
            $challenge = $req->query('hub_challenge') ?: $req->query('hub.challenge');

            if ($mode === 'subscribe' && $token === config('app.fb_webhook_verify', env('FB_WEBHOOK_VERIFY'))) {
                return response($challenge, 200);
            }
            return response('Forbidden', 403);
        }

        WebhookLog::create([
            'provider' => 'facebook',
            'type'     => data_get($req->all(),'object'),
            'payload'  => $req->all(),
        ]);

        return response()->json(['received'=>true]);
    }
}
