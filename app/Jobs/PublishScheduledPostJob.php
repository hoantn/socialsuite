<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\ScheduledPost;
use App\Models\FbPage;
use App\Models\FbPageToken;
use App\Models\FbPost;

class PublishScheduledPostJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 15;

    public function __construct(public int $scheduledId) {}

    protected function graphV(): string
    {
        return config('socialsuite.facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v20.0'));
    }

    public function handle(): void
    {
        $sch = ScheduledPost::find($this->scheduledId);
        if (!$sch) return;

        // Skip if canceled/finished
        if (in_array($sch->status, ['canceled','published'])) return;

        $sch->status = 'processing';
        $sch->save();

        $pageId = $sch->page_id;
        $page = FbPage::where('page_id', $pageId)->first();
        $token = FbPageToken::where('page_id', $pageId)->value('access_token');

        if (!$token) {
            $sch->status = 'failed';
            $sch->error_message = 'Missing page token';
            $sch->save();
            return;
        }

        try {
            $message = $sch->message;

            if ($sch->media_path && file_exists(storage_path('app/'.$sch->media_path))) {
                $fullPath = storage_path('app/'.$sch->media_path);
                $filename = basename($fullPath);
                $resp = Http::attach('source', file_get_contents($fullPath), $filename)
                    ->asMultipart()
                    ->post('https://graph.facebook.com/'.$this->graphV()."/{$pageId}/photos", [
                        'caption' => $message,
                        'access_token' => $token,
                    ]);
                $type = 'photo';
            } else {
                $resp = Http::asForm()->post('https://graph.facebook.com/'.$this->graphV()."/{$pageId}/feed", [
                    'message' => $message,
                    'access_token' => $token,
                ]);
                $type = 'feed';
            }

            $ok = $resp->ok();
            $data = $resp->json();

            // Save to fb_posts (history)
            FbPost::create([
                'page_id' => $pageId,
                'page_name' => $page->name ?? null,
                'post_id' => $data['id'] ?? ($data['post_id'] ?? null),
                'message' => $message,
                'type'    => $type,
                'status'  => $ok ? 'published' : 'error',
                'error_code' => $ok ? null : (data_get($data,'error.code')),
                'error_message' => $ok ? null : (data_get($data,'error.message')),
                'response'=> $data,
            ]);

            $sch->status = $ok ? 'published' : 'failed';
            $sch->error_code = $ok ? null : (data_get($data,'error.code'));
            $sch->error_message = $ok ? null : (data_get($data,'error.message'));
            $sch->response = $data;
            $sch->save();

        } catch (\Throwable $e) {
            Log::error('PublishScheduledPostJob error: '.$e->getMessage());
            $sch->status = 'failed';
            $sch->error_message = $e->getMessage();
            $sch->save();
            throw $e; // let queue retry
        }
    }
}
