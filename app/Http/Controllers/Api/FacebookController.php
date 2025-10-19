<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Routing\Controller;

class FacebookController extends Controller
{
    /**
     * Return the list of pages available to the current user.
     * For now, in DEV mode (no token), we return demo data so the UI works.
     * Later, once OAuth is wired, read token from DB and call Graph API:
     *   GET https://graph.facebook.com/v20.0/me/accounts?fields=id,name,access_token,perms
     */
    public function listPages(Request $request)
    {
        // Try to read a token from env/DB for quick manual testing
        $token = env('FB_USER_TOKEN');

        if ($token) {
            try {
                $res = Http::timeout(10)->get('https://graph.facebook.com/v20.0/me/accounts', [
                    'access_token' => $token,
                    'fields' => 'id,name,access_token,perms,category',
                    'limit' => 50,
                ]);

                if (!$res->successful()) {
                    Log::warning('Graph me/accounts failed', ['status' => $res->status(), 'body' => $res->body()]);
                    return response()->json([
                        'ok' => false,
                        'error' => 'graph_error',
                        'status' => $res->status(),
                        'message' => $res->json('error.message') ?? 'Graph call failed',
                        'data' => $res->json(),
                    ], 200);
                }

                return response()->json(['ok' => true, 'data' => $res->json('data') ?? []]);
            } catch (\Throwable $e) {
                Log::error('Graph me/accounts exception', ['e' => $e]);
                return response()->json(['ok' => false, 'error' => 'exception', 'message' => $e->getMessage()], 200);
            }
        }

        // DEV fallback (no token). Keeps UI usable.
        return response()->json(['ok' => true, 'data' => [
            ['id' => '105133778925325', 'name' => 'Phạm Hồng Nhung (demo)', 'access_token' => null, 'perms' => ['MANAGE','CONTENT']],
            ['id' => '843580202169817', 'name' => 'Cherry My - 어하은 (demo)', 'access_token' => null, 'perms' => ['MANAGE']],
            ['id' => '799104546622744', 'name' => 'Đài Tiếng Nói VN (demo)', 'access_token' => null, 'perms' => ['MANAGE','MODERATE']],
        ]]);
    }

    /**
     * Subscribe app webhook to a page.
     * In DEV mode without app credentials, we only simulate and log input.
     *
     * Expected input: page_id, page_access_token (optional for now)
     */
    public function subscribe(Request $request)
    {
        $pageId = $request->string('page_id')->toString();
        $pageToken = $request->string('page_access_token')->toString();

        Log::info('Subscribe request', ['page_id' => $pageId]);

        if (!$pageId) {
            return response()->json(['ok' => false, 'message' => 'page_id is required'], 422);
        }

        // In production: call
        //   POST https://graph.facebook.com/v20.0/{page-id}/subscribed_apps
        // with: access_token = {page-access-token}
        // and optionally subscribed_fields=[]
        // For now, pretend success.
        return response()->json(['ok' => true, 'page_id' => $pageId, 'subscribed' => true]);
    }
}
