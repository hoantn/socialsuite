<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\FacebookClient;
use App\Services\FacebookActions;

class FacebookController extends Controller
{
    protected FacebookClient $client;
    protected FacebookActions $actions;

    public function __construct(FacebookClient $client)
    {
        $this->client = $client;
        $this->actions = new FacebookActions($client);
    }

    // POST /api/facebook/subscribe
    // Body: { "page_ids": ["123","456"] }
    public function subscribe(Request $request)
    {
        $pageIds = $request->input('page_ids', []);
        if (!is_array($pageIds) || empty($pageIds)) {
            // if not provided, subscribe all active pages having tokens
            $pageIds = DB::table('account_page')->pluck('fb_page_id')->unique()->values()->all();
        }

        $fields = [
            'feed',
            'conversations',
            'messages',
            'message_deliveries',
            'message_reads',
            'mention',
            'comments'
        ];

        $subscribed = [];
        $failed = [];

        foreach ($pageIds as $pid) {
            $token = DB::table('account_page')->where('fb_page_id', $pid)->value('page_access_token');
            if (!$token) { $failed[] = ['page_id' => $pid, 'error' => 'no_page_token']; continue; }

            try {
                $this->actions->subscribePage($pid, $token, $fields);
                $subscribed[] = $pid;
            } catch (\Throwable $e) {
                Log::warning('Subscribe page failed', ['page_id' => $pid, 'err' => $e->getMessage()]);
                $failed[] = ['page_id' => $pid, 'error' => $e->getMessage()];
            }
        }

        return response()->json(['ok' => true, 'subscribed' => $subscribed, 'failed' => $failed]);
    }
}
