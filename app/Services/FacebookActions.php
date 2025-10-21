<?php

namespace App\Services;

use App\Services\FacebookClient;
use Illuminate\Support\Facades\Log;

class FacebookActions
{
    protected FacebookClient $client;

    public function __construct(FacebookClient $client)
    {
        $this->client = $client;
    }

    public function subscribePage(string $pageId, string $pageAccessToken, array $fields): void
    {
        $this->client->sdk()->post("/{$pageId}/subscribed_apps", [
            'subscribed_fields' => implode(',', $fields),
        ], $pageAccessToken);
    }

    public function sendMessage(string $pageAccessToken, string $psid, string $text): void
    {
        $this->client->sdk()->post('/me/messages', [
            'recipient' => ['id' => $psid],
            'message'   => ['text' => $text],
        ], $pageAccessToken);
    }

    public function commentReply(string $commentId, string $pageAccessToken, string $text): void
    {
        $this->client->sdk()->post("/{$commentId}/comments", ['message' => $text], $pageAccessToken);
    }

    public function likeObject(string $objectId, string $pageAccessToken): void
    {
        $this->client->sdk()->post("/{$objectId}/likes", [], $pageAccessToken);
    }
}
