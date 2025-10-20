<?php

namespace App\Jobs;

use App\Models\FbPage;
use App\Services\FacebookClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PostToPageJob implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $pageId;
    public array $payload;

    public function __construct(string $pageId, array $payload) {
        $this->pageId = $pageId;
        $this->payload = $payload;
    }

    public function handle(FacebookClient $client): void {
        $page = FbPage::findOrFail($this->pageId);
        $client->withPageToken($page->page_access_token);
        $fb = $client->sdk();
        // Minimal example: publish a feed post
        $fb->post("/{$this->pageId}/feed", $this->payload);
    }
}
