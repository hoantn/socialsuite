<?php
namespace App\Services;
use Illuminate\Support\Facades\Http;

class MetaGraph {
  public function version(): string { return env('META_GRAPH_VERSION', 'v18.0'); }

  public function meAccounts(string $userToken): array {
    $v = $this->version();
    $res = Http::get("https://graph.facebook.com/$v/me/accounts", [
      'access_token' => $userToken,
      'fields' => 'id,name,access_token,perms',
      'limit' => 100
    ]);
    return $res->json();
  }

  public function subscribeApp(string $pageToken): bool {
    $v = $this->version();
    $res = Http::withToken($pageToken)->post("https://graph.facebook.com/$v/me/subscribed_apps", [
      'subscribed_fields' => 'messages,messaging_postbacks,messaging_handovers,messaging_optins,message_deliveries,messaging_referrals,messaging_seen'
    ]);
    return $res->ok();
  }

  public function sendMessage(string $pageToken, string $psid, array $message): array {
    $v = $this->version();
    $endpoint = "https://graph.facebook.com/$v/me/messages";
    $res = Http::asJson()->withToken($pageToken)->post($endpoint, [
      'recipient' => ['id' => $psid],
      'messaging_type' => 'RESPONSE',
      'message' => $message
    ]);
    return [$res->status(), $res->json()];
  }
}