<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

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
        $row = DB::table('scheduled_posts')->where('id',$this->scheduledId)->first();
        if (!$row || $row->status !== 'queued') return;

        // Không đăng trước thời điểm
        if (Carbon::parse($row->publish_at, 'UTC')->isFuture()) return;

        $page = DB::table('fb_pages')->where('page_id',$row->page_id)->orWhere('id',$row->page_id)->first();
        if (!$page) {
            DB::table('scheduled_posts')->where('id',$row->id)->update([
                'status'=>'failed','error'=>'Page not found','updated_at'=>now()
            ]);
            return;
        }

        $graph = 'https://graph.facebook.com/v18.0';
        $token = $page->access_token;

        $media = json_decode($row->media_paths, true) ?: [];
        $type  = $row->media_type;

        try {
            if ($type === 'image') {
                if (count($media) === 1) {
                    // Một ảnh
                    $f = $media[0];
                    $filePath = Storage::disk('local')->path($f['path']);
                    $res = Http::attach('source', fopen($filePath,'r'), basename($filePath))
                        ->asMultipart()
                        ->post("{$graph}/{$page->page_id}/photos", [
                            'caption' => $row->message,
                            'access_token' => $token,
                            'published' => true,
                        ]);
                    if ($res->failed()) throw new \Exception($res->body());
                } else {
                    // Album (attached_media)
                    $mediaFbids = [];
                    foreach ($media as $f) {
                        $filePath = Storage::disk('local')->path($f['path']);
                        $res = Http::attach('source', fopen($filePath,'r'), basename($filePath))
                            ->asMultipart()
                            ->post("{$graph}/{$page->page_id}/photos", [
                                'published' => false,
                                'access_token' => $token,
                            ]);
                        if ($res->failed()) throw new \Exception($res->body());
                        $mediaFbids[] = ['media_fbid' => $res->json('id')];
                    }
                    $payload = [
                        'message'       => $row->message,
                        'attached_media'=> json_encode($mediaFbids),
                        'access_token'  => $token,
                    ];
                    $res2 = Http::asForm()->post("{$graph}/{$page->page_id}/feed", $payload);
                    if ($res2->failed()) throw new \Exception($res2->body());
                }
            } else { // video
                if (count($media) !== 1) throw new \Exception('Only 1 video allowed');
                $f = $media[0];
                $filePath = Storage::disk('local')->path($f['path']);
                $res = Http::attach('source', fopen($filePath,'r'), basename($filePath))
                    ->asMultipart()
                    ->post("{$graph}/{$page->page_id}/videos", [
                        'description' => $row->message,
                        'access_token' => $token,
                        'published' => true,
                    ]);
                if ($res->failed()) throw new \Exception($res->body());
            }

            DB::table('scheduled_posts')->where('id',$row->id)->update([
                'status'=>'posted','updated_at'=>now(),
            ]);
        } catch (\Throwable $e) {
            DB::table('scheduled_posts')->where('id',$row->id)->update([
                'status'=>'failed','error'=>substr($e->getMessage(),0,1000),'updated_at'=>now(),
            ]);
        }
    }
}
