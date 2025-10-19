<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Inertia;
use App\Services\MetaGraph;
use App\Models\{Page,UserSocial};

class OAuthController extends Controller {
  public function redirect() {
    $state = Str::random(40);
    session(['fb_oauth_state' => $state]);

    $params = [
      'client_id'     => env('META_APP_ID'),
      'redirect_uri'  => route('facebook.callback'),
      'scope'         => 'pages_show_list,pages_read_engagement,pages_manage_metadata',
      'response_type' => 'code',
      'auth_type'     => 'rerequest',
      'state'         => $state,
    ];
    $v = env('META_GRAPH_VERSION', 'v18.0');
    $url = "https://www.facebook.com/$v/dialog/oauth?".http_build_query($params);
    Log::info('FB OAuth redirect', ['url' => $url]);
    return redirect()->away($url);
  }

  public function callback(Request $request, MetaGraph $graph) {
    if (!$request->filled('state') || $request->state !== session('fb_oauth_state')) {
      Log::warning('FB OAuth invalid state', ['incoming' => $request->state, 'session' => session('fb_oauth_state')]);
      abort(400, 'Invalid state');
    }
    session()->forget('fb_oauth_state');

    $v = env('META_GRAPH_VERSION', 'v18.0');
    $resp = Http::get("https://graph.facebook.com/$v/oauth/access_token", [
      'client_id'     => env('META_APP_ID'),
      'client_secret' => env('META_APP_SECRET'),
      'redirect_uri'  => route('facebook.callback'),
      'code'          => $request->code,
    ])->throw()->json();

    Log::info('FB OAuth token resp', $resp);
    $short = $resp['access_token'] ?? null;
    if (!$short) abort(500, 'Cannot get short-lived user token');

    $x = $graph->exchangeLongLivedUserToken($short);
    Log::info('FB exchange long-lived', $x);
    $userToken = $x['access_token'] ?? $short;
    $ttl = $x['expires_in'] ?? null;

    UserSocial::updateOrCreate(
      ['user_id' => 1, 'provider' => 'facebook'],
      ['access_token' => $userToken, 'long_lived' => true, 'expires_at' => $ttl ? now()->addSeconds($ttl) : null]
    );

    session(['fb_user_token' => $userToken]);
    $accounts = $graph->meAccounts($userToken);
    Log::info('FB /me/accounts', $accounts ?? []);
    $list = $accounts['data'] ?? [];

    return Inertia::render('Pages/Connect', ['accounts' => $list]);
  }

  public function importPages(Request $r, MetaGraph $graph) {
    $data = $r->validate(['pages'=>'required|array']);
    $userSocial = UserSocial::where('user_id',1)->where('provider','facebook')->first();
    $userToken = $userSocial?->access_token ?? session('fb_user_token');

    $imported = 0;
    foreach($data['pages'] as $p) {
      $tokenData = $graph->longLivedPageToken($p['id'], $userToken);
      Log::info('FB page token', ['page_id'=>$p['id'], 'resp'=>$tokenData]);
      $pageToken = $tokenData['access_token'] ?? ($p['access_token'] ?? null);
      $expiresAt = isset($tokenData['expires_in']) ? now()->addSeconds($tokenData['expires_in']) : null;

      $page = Page::updateOrCreate(
        ['page_id' => $p['id']],
        [
          'user_id'=>1,
          'channel'=>'messenger',
          'name'=>$p['name']??('Page '.$p['id']),
          'access_token'=>$pageToken,
          'perms'=>$p['perms']??[],
          'token_expires_at'=>$expiresAt,
        ]
      );

      $ok = false;
      if ($page->access_token) {
        $ok = $graph->subscribeApp($page->access_token);
        if ($ok) $page->update(['subscribed'=>true]);
      }
      Log::info('Imported page (subscribe status)', ['id'=>$page->id, 'ok'=>$ok]);
      $imported++;
    }
    return redirect()->route('pages.index')->with('success', "Đã xử lý {$imported} page(s).");
  }
}