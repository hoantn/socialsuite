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

class HandleMessageEventJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $entry;
    public array $message;

    public $tries = 3;
    public $backoff = 5;

    public function __construct(array $entry, array $message)
    {
        $this->entry = $entry;
        $this->message = $message;
    }

    public function handle(FacebookActions $actions): void
    {
        $pageId = $this->entry['id'] ?? null;
        if (!$pageId) return;

        $token = DB::table('account_page')->where('fb_page_id', $pageId)->value('page_access_token');
        if (!$token) return;

        $sender = $this->message['sender']['id'] ?? null;
        $text = $this->message['message']['text'] ?? ($this->message['postback']['title'] ?? '');
        if (!$sender) return;

        // Very simple auto-reply example. Replace by rules from page_configs.
        $reply = $text ? "Cảm ơn bạn: {$text}" : "Cảm ơn bạn đã nhắn tin!";
        try {
            // basic anti-spam: small random delay 1-3s
            usleep(random_int(1000, 3000) * 1000);
            $actions->sendMessage($token, $sender, $reply);
        } catch (\Throwable $e) {
            Log::warning('HandleMessageEventJob error', ['e' => $e->getMessage()]);
            $this->release(10);
        }
    }
}
