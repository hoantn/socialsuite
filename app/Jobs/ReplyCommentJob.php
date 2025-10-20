<?php

namespace App\Jobs;

use App\Models\FbPage;
use App\Services\FacebookClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReplyCommentJob implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $pageId;
    public string $commentId;
    public array $payload;

    public function __construct(string $pageId, string $commentId, array $payload) {
        $this->pageId = $pageId;
        $this->commentId = $commentId;
        $this->payload = $payload;
    }

    public function handle(FacebookClient $client): void {
        $page = FbPage::findOrFail($this->pageId);
        $client->withPageToken($page->page_access_token);
        $fb = $client->sdk();
        $fb->post("/{$this->commentId}/comments", $this->payload);
    }
}
