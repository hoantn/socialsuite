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

    protected function uploadUnpublishedPhoto(string $pageId, string $token, string $fullPath): array
    {
        $filename = basename($fullPath);
        $resp = Http::attach('source', file_get_contents($fullPath), $filename)
            ->asMultipart()
            ->post('https://graph.facebook.com/'.$this->graphV()."/{$pageId}/photos", [
                'published' => 'false',
                'access_token' => $token,
            ]);
        return [$resp->ok(), $resp->json()];
    }

    public function handle(): void
    {
        $sch = ScheduledPost::find($this->scheduledId);
        if (!$sch) return;

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
            $data = null;
            $ok = false;
            $type = 'feed';

            $mediaPaths = $sch->media_paths ?: [];
            if (!$mediaPaths && $sch->media_path) {
                $mediaPaths = [$sch->media_path]; // backward compat
            }

            if (count($mediaPaths) > 1) {
                $attached = [];
                foreach ($mediaPaths as $rel) {
                    $full = storage_path('app/'.$rel);
                    if (!file_exists($full)) continue;
                    [$uok, $udata] = $this->uploadUnpublishedPhoto($pageId, $token, $full);
                    if ($uok && isset($udata['id'])) {
                        $attached[] = ['media_fbid' => $udata['id']];
                    }
                }
                if (count($attached) == 0) {
                    $ok = false; $data = ['error'=>['message'=>'No images uploaded']];
                } else {
                    $form = ['access_token'=>$token, 'message'=>$message];
                    foreach ($attached as $i => $item) {
                        $form['attached_media['.$i.']'] = json_encode($item);
                    }
                    $resp = Http::asForm()->post('https://graph.facebook.com/'.$this->graphV()."/{$pageId}/feed", $form);
                    $ok = $resp->ok();
                    $data = $resp->json();
                    $type = 'album';
                }
            } elseif (count($mediaPaths) == 1) {
                $full = storage_path('app/'.$mediaPaths[0]);
                if (file_exists($full)) {
                    $filename = basename($full);
                    $resp = Http::attach('source', file_get_contents($full), $filename)
                        ->asMultipart()
                        ->post('https://graph.facebook.com/'.$this->graphV()."/{$pageId}/photos", [
                            'caption' => $message,
                            'access_token' => $token,
                        ]);
                    $ok = $resp->ok();
                    $data = $resp->json();
                    $type = 'photo';
                } else {
                    $ok = false; $data = ['error'=>['message'=>'Image not found']];
                }
            } else {
                $resp = Http::asForm()->post('https://graph.facebook.com/'.$this->graphV()."/{$pageId}/feed", [
                    'message' => $message,
                    'access_token' => $token,
                ]);
                $ok = $resp->ok();
                $data = $resp->json();
                $type = 'feed';
            }

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
            $sch->media_type = (count($mediaPaths) > 1) ? 'album' : ((count($mediaPaths)==1)?'photo':null);
            $sch->save();

        } catch (\Throwable $e) {
            Log::error('PublishScheduledPostJob error: '.$e->getMessage());
            $sch->status = 'failed';
            $sch->error_message = $e->getMessage();
            $sch->save();
            throw $e;
        }
    }
}
