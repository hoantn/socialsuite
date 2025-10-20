<?php

namespace App\Jobs;

use App\Models\FbPage;
use App\Services\FacebookClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendPageMessageJob implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $pageId;
    public array $message;

    public function __construct(string $pageId, array $message) {
        $this->pageId = $pageId;
        $this->message = $message;
    }

    public function handle(FacebookClient $client): void {
        $page = FbPage::findOrFail($this->pageId);
        $client->withPageToken($page->page_access_token);
        $fb = $client->sdk();
        $fb->post("/{$this->pageId}/messages", $this->message);
    }
}
