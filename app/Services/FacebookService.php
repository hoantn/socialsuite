<?php
namespace App\Services;
use Illuminate\Support\Facades\Http;

class FacebookService {
  public function sendMessage(string $pageToken, string $psid, string $text): array {
    $version = env('META_GRAPH_VERSION', 'v18.0');
    $endpoint = "https://graph.facebook.com/$version/me/messages";
    $res = Http::asJson()->withToken($pageToken)
      ->post($endpoint, ['recipient'=>['id'=>$psid], 'messaging_type'=>'MESSAGE_TAG', 'tag'=>'ACCOUNT_UPDATE', 'message'=>['text'=>$text]]);
    return [$res->status(), $res->json()];
  }
}