<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\{Page,UserSocial};
use App\Services\MetaGraph;

class PageActionsController extends Controller {
  public function subscribe(Page $page, MetaGraph $graph) {
    if ($page->access_token && $graph->subscribeApp($page->access_token)) {
      $page->update(['subscribed'=>true]);
    }
    return redirect()->route('pages.index');
  }
  public function unsubscribe(Page $page, MetaGraph $graph) {
    if ($page->access_token && $graph->unsubscribeApp($page->access_token)) {
      $page->update(['subscribed'=>false]);
    }
    return redirect()->route('pages.index');
  }
  public function refreshToken(Page $page, MetaGraph $graph) {
    $userToken = optional(UserSocial::where('user_id',1)->where('provider','facebook')->first())->access_token;
    if (!$userToken) return redirect()->route('pages.index');
    $t = $graph->longLivedPageToken($page->page_id, $userToken);
    if (isset($t['access_token'])) {
      $page->update([
        'access_token' => $t['access_token'],
        'token_expires_at' => isset($t['expires_in']) ? now()->addSeconds($t['expires_in']) : null,
      ]);
    }
    return redirect()->route('pages.index');
  }
}