<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use App\Services\MetaGraph;
use App\Models\{Page,UserSocial};

class OAuthController extends Controller {

  public function importPages(Request $r, MetaGraph $graph) {
    $data = $r->validate(['pages'=>'required|array']);
    Log::info('IMPORT_PAGES_REQUEST', $data);

    $userSocial = UserSocial::where('user_id',1)->where('provider','facebook')->first();
    $userToken = $userSocial?->access_token ?? session('fb_user_token');

    $imported = 0;
    foreach($data['pages'] as $p) {
      try {
        Log::info('IMPORT_PAGE_ROW_START', $p);

        $tokenData = $graph->longLivedPageToken($p['id'], $userToken);
        Log::info('FB page token', ['page_id'=>$p['id'], 'resp'=>$tokenData]);

        $pageToken = $tokenData['access_token'] ?? ($p['access_token'] ?? null);
        $expiresAt = isset($tokenData['expires_in']) ? now()->addSeconds($tokenData['expires_in']) : null;

        $payload = [
          'user_id'=>1,                         // TODO: thay bằng Auth::id() nếu bạn có auth
          'channel'=>'messenger',
          'name'=>$p['name']??('Page '.$p['id']),
          'access_token'=>$pageToken,
          'perms'=>$p['tasks']??[],            // map tasks -> perms
          'token_expires_at'=>$expiresAt,
        ];

        $page = Page::updateOrCreate(['page_id' => $p['id']], $payload);
        Log::info('IMPORT_PAGE_SAVED', $page->toArray());

        $ok = false;
        if ($page->access_token) {
          try { $ok = $graph->subscribeApp($page->access_token); } catch (\Throwable $e) { $ok=false; Log::warning('subscribe failed', ['e'=>$e->getMessage()]); }
          if ($ok) $page->update(['subscribed'=>true]);
        }
        Log::info('Imported page (subscribe status)', ['page_id'=>$page->page_id, 'ok'=>$ok]);
        $imported++;
      } catch (\Throwable $e) {
        Log::error('IMPORT_PAGE_ERROR', ['page'=>$p,'msg'=>$e->getMessage()]);
      }
    }

    return redirect()->route('pages.index')->with('success', "Đã xử lý {$imported} page(s).");
  }
}