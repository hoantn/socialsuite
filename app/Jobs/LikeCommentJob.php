<?php

namespace App\Jobs;

use App\Models\FbPage;
use App\Services\FacebookClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LikeCommentJob implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $pageId;
    public string $commentId;

    public function __construct(string $pageId, string $commentId) {
        $this->pageId = $pageId;
        $this->commentId = $commentId;
    }

    public function handle(FacebookClient $client): void {
        $page = FbPage::findOrFail($this->pageId);
        $client->withPageToken($page->page_access_token);
        $fb = $client->sdk();
        $fb->post(f"/{self::escape($this->commentId)}/likes", []);
    }

    private static function escape($id){ return preg_replace('~[^0-9_]~', '', $id); }
}
