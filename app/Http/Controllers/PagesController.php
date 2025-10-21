<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\FbAccount;
use App\Models\FbPage;
use App\Services\FacebookClient;

/**
 * PagesController – fixed to use the DI FacebookClient (which in DEV uses verify=false)
 * so you won't see "SSL certificate problem: unable to get local issuer certificate".
 */
class PagesController extends Controller
{
    protected FacebookClient $fb;

    public function __construct(FacebookClient $fb)
    {
        $this->fb = $fb;
    }

    public function sync(Request $request)
    {
        $accountId = (int) $request->session()->get('fb_account_id');
        $acc = FbAccount::findOrFail($accountId);
        $userToken = $acc->user_access_token;

        // Gắn token user làm default cho SDK
        $this->fb->sdk()->setDefaultAccessToken($userToken);

        // Lấy danh sách page (kèm page token + perms)
        // (Có thể dùng biến thể truyền token ở tham số 3 nếu bạn muốn tách bạch)
        $resp = $this->fb->sdk()->get('/me/accounts?fields=id,name,category,picture{url},username,connected_instagram_account,access_token,perms');
        $data = $resp->getDecodedBody();
        $pages = $data['data'] ?? [];

        DB::transaction(function () use ($pages, $acc) {
            foreach ($pages as $p) {
                $pageId = (string)($p['id'] ?? '');
                if ($pageId === '') { continue; }

                // upsert fb_pages
                $page = FbPage::updateOrCreate(
                    ['fb_page_id' => $pageId],
                    [
                        'name'       => $p['name'] ?? null,
                        'avatar_url' => $p['picture']['data']['url'] ?? null,
                    ]
                );

                // upsert account_page pivot
                DB::table('account_page')->updateOrInsert(
                    ['fb_account_id' => $acc->id, 'fb_page_id' => $page->id],
                    [
                        'page_access_token' => $p['access_token'] ?? null,
                        'perms'             => json_encode($p['perms'] ?? []),
                        'updated_at'        => now(),
                        'created_at'        => now(),
                    ]
                );
            }
        });

        return redirect()->route('pages.index')->with('ok', true);
    }
}
