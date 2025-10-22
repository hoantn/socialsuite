<?php

namespace App\Jobs;

use App\Models\FbPage;
use App\Models\ScheduledPost;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Throwable;

class PublishScheduledPost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $scheduledId;

    public function __construct(int $scheduledId)
    {
        $this->scheduledId = $scheduledId;
        $this->onQueue('default');
    }

    public function handle(): void
    {
        $post = ScheduledPost::find($this->scheduledId);
        if (!$post || $post->status !== 'queued') { return; }

        $page = FbPage::where('page_id', $post->page_id)->first();
        if (!$page || !$page->access_token) {
            $post->update(['status' => 'failed', 'error' => 'Page token missing.']);
            return;
        }

        $token = $page->access_token;
        $graph = 'https://graph.facebook.com/v18.0';

        try {
            $mediaPaths = json_decode($post->media_paths, true) ?: [];
            if (count($mediaPaths) === 0) {
                $post->update(['status' => 'failed', 'error' => 'No images uploaded']);
                return;
            }

            if (count($mediaPaths) === 1) {
                $path = Storage::disk('local').path($mediaPaths[0]);
            } else {
                $path = null;
            }

            if (count($mediaPaths) === 1) {
                $filePath = Storage::disk('local').path($mediaPaths[0]);
                $resp = Http::asMultipart()->post("{$graph}/{$page->page_id}/photos", [
                    ['name' => 'source', 'contents' => fopen($filePath, 'r')],
                    ['name' => 'message', 'contents' => (string) $post->message],
                    ['name' => 'access_token', 'contents' => $token],
                ]);
                if ($resp->failed()) {
                    $post->update(['status' => 'failed', 'error' => $resp->body()]);
                    return;
                }
            } else {
                $attached = [];
                foreach ($mediaPaths as $p) {
                    $filePath = Storage::disk('local').path($p);
                    $r = Http::asMultipart()->post("{$graph}/{$page->page_id}/photos", [
                        ['name' => 'source', 'contents' => fopen($filePath, 'r')],
                        ['name' => 'published', 'contents' => 'false'],
                        ['name' => 'access_token', 'contents' => $token],
                    ]);
                    if ($r->failed()) {
                        $post->update(['status' => 'failed', 'error' => $r->body()]);
                        return;
                    }
                    $attached[] = ['media_fbid' => $r->json('id')];
                }
                $payload = [
                    'attached_media' => json_encode($attached),
                    'message' => (string) $post->message,
                    'access_token' => $token,
                ];
                $resp = Http::asForm()->post("{$graph}/{$page->page_id}/feed", $payload);
                if ($resp->failed()) {
                    $post->update(['status' => 'failed', 'error' => $resp->body()]);
                    return;
                }
            }

            $post->update(['status' => 'success', 'error' => null]);
        } catch (Throwable $e) {
            $post->update(['status' => 'failed', 'error' => $e->getMessage()]);
            throw $e;
        }
    }
}
