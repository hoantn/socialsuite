<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\FacebookActions;

class HandleCommentEventJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $entry;
    public array $change;

    public $tries = 2;
    public $backoff = 10;

    public function __construct(array $entry, array $change)
    {
        $this->entry = $entry;
        $this->change = $change;
    }

    public function handle(FacebookActions $actions): void
    {
        $pageId = $this->entry['id'] ?? null;
        if (!$pageId) return;

        $token = DB::table('account_page')->where('fb_page_id', $pageId)->value('page_access_token');
        if (!$token) return;

        $val = $this->change['value'] ?? [];
        $verb = $val['verb'] ?? '';
        $commentId = $val['comment_id'] ?? null;
        $from = $val['from']['name'] ?? 'bạn';

        if ($verb === 'add' && $commentId) {
            try {
                usleep(random_int(1500, 4000) * 1000);
                $actions->commentReply($commentId, $token, "Cảm ơn {$from} đã bình luận!");
            } catch (\Throwable $e) {
                Log::warning('HandleCommentEventJob error', ['e' => $e->getMessage()]);
                $this->release(15);
            }
        }
    }
}
