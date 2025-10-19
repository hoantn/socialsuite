<?php
namespace App\Services;
use Illuminate\Support\Facades\Http;

class MetaGraph {
  public function version(): string { return env('META_GRAPH_VERSION', 'v18.0'); }

  public function debugToken(string $inputToken): array {
    $v = $this->version();
    $appId = env('META_APP_ID'); $appSecret = env('META_APP_SECRET');
    $appToken = $appId.'|'.$appSecret;
    return Http::get("https://graph.facebook.com/$v/debug_token", [
      'input_token' => $inputToken,
      'access_token' => $appToken,
    ])->throw()->json();
  }

  public function exchangeLongLivedUserToken(string $shortLived): array {
    $v = $this->version();
    $appId = env('META_APP_ID'); $appSecret = env('META_APP_SECRET');
    return Http::get("https://graph.facebook.com/$v/oauth/access_token", [
      'grant_type' => 'fb_exchange_token',
      'client_id' => $appId,
      'client_secret' => $appSecret,
      'fb_exchange_token' => $shortLived,
    ])->throw()->json();
  }

  public function meAccounts(string $userToken): array {
    $v = $this->version();
    return Http::get("https://graph.facebook.com/$v/me/accounts", [
      'access_token' => $userToken,
      'fields' => 'id,name,access_token,perms,category',
      'limit' => 100
    ])->throw()->json();
  }

  public function longLivedPageToken(string $pageId, string $userLongLivedToken): array {
    $v = $this->version();
    return Http::get("https://graph.facebook.com/$v/$pageId", [
      'fields' => 'access_token',
      'access_token' => $userLongLivedToken,
    ])->throw()->json();
  }

  public function subscribeApp(string $pageToken): bool {
    $v = $this->version();
    return Http::withToken($pageToken)->post("https://graph.facebook.com/$v/me/subscribed_apps", [
      'subscribed_fields' => 'messages,messaging_postbacks,messaging_handovers,messaging_optins,message_deliveries,messaging_referrals,messaging_seen'
    ])->throw()->ok();
  }

  public function unsubscribeApp(string $pageToken): bool {
    $v = $this->version();
    return Http::withToken($pageToken)->delete("https://graph.facebook.com/$v/me/subscribed_apps")->throw()->ok();
  }

  public function sendMessage(string $pageToken, string $psid, array $message): array {
    $v = $this->version();
    $res = Http::asJson()->withToken($pageToken)->post("https://graph.facebook.com/$v/me/messages", [
      'recipient' => ['id' => $psid],
      'messaging_type' => 'RESPONSE',
      'message' => $message
    ])->throw();
    return [$res->status(), $res->json()];
  }
}