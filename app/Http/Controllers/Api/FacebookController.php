<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\FacebookService;
use App\Models\Page;
use App\Models\FbToken;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FacebookController extends Controller
{
    protected $fbService;
    public function __construct(FacebookService $fbService) {
        $this->fbService = $fbService;
    }

    public function oauthCallback(Request $request) {
        $shortToken = $request->input('access_token');
        if (!$shortToken) {
            return response()->json(['error' => 'missing access token'], 400);
        }
        $long = $this->fbService->exchangeLongLivedToken($shortToken);
        $model = FbToken::create([
            'user_id' => auth()->id() ?? null,
            'access_token' => $long,
            'type' => 'user',
            'expires_at' => Carbon::now()->addDays(60),
        ]);
        return response()->json(['ok'=>true,'token_id'=>$model->id]);
    }

    public function importPages(Request $request) {
        $token = $request->input('token');
        if (!$token) {
            $tokenRow = FbToken::where('type','user')->latest()->first();
            if (!$tokenRow) return response()->json(['error'=>'no token'], 400);
            $token = $tokenRow->access_token;
        }
        $data = $this->fbService->getUserPages($token);
        if (!isset($data['data'])) {
            return response()->json(['error'=>'no_data','raw'=>$data], 500);
        }
        foreach ($data['data'] as $pg) {
            Page::updateOrCreate(
                ['page_id' => $pg['id']],
                [
                    'name' => $pg['name'] ?? null,
                    'page_access_token' => $pg['access_token'] ?? null,
                    'perms' => $pg['perms'] ?? null,
                ]
            );
        }
        return response()->json(['ok'=>true,'count'=>count($data['data'])]);
    }

    public function pages() {
        $pages = Page::orderBy('name')->get();
        return response()->json(['data'=>$pages]);
    }

    public function subscribe(Request $request) {
        $pageId = $request->input('page_id');
        if (!$pageId) return response()->json(['error'=>'page_id required'],400);
        $page = Page::where('page_id',$pageId)->first();
        if (!$page || !$page->page_access_token) return response()->json(['error'=>'no page token'],400);
        $r = $this->fbService->subscribePage($pageId, $page->page_access_token);
        return response()->json(['ok'=>true,'result'=>$r]);
    }

    public function webhook(Request $request) {
        if ($request->isMethod('get')) {
            $mode = $request->query('hub_mode');
            $token = $request->query('hub_verify_token');
            $challenge = $request->query('hub_challenge');
            if ($mode === 'subscribe' && $token === env('FB_VERIFY_TOKEN')) {
                return response($challenge, 200);
            }
            return response('Forbidden', 403);
        }

        $payload = $request->all();
        DB::table('webhooks')->insert([
            'object' => $payload['object'] ?? null,
            'payload' => json_encode($payload),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if (isset($payload['entry'])) {
            foreach ($payload['entry'] as $entry) {
                if (isset($entry['messaging'])) {
                    foreach ($entry['messaging'] as $msg) {
                        $sender = $msg['sender']['id'] ?? null;
                        $recipient = $msg['recipient']['id'] ?? null;
                        $page_id = $recipient;
                        $conv = Conversation::firstOrCreate([
                            'page_id' => $page_id,
                            'sender_id' => $sender,
                            'recipient_id' => $recipient,
                        ], ['meta' => null]);
                        $body = $msg['message']['text'] ?? null;
                        Message::create([
                            'conversation_id' => $conv->id,
                            'type' => 'text',
                            'body' => $body,
                            'raw' => $msg,
                        ]);
                    }
                }
            }
        }
        return response()->json(['status' => 'received']);
    }
}
