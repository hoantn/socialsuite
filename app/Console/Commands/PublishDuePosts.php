<?php
namespace App\Console\Commands;
use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
class PublishDuePosts extends Command
{
    protected $signature = 'socialsuite:publish-due';
    protected $description = '[SOCIALSUITE][GPT] Publish scheduled posts whose time has come';
    public function handle(): int
    {
        $due = Post::where('status','scheduled')
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at','<=', now())
            ->limit(20)->get();
        foreach ($due as $post) {
            $page = $post->page;
            if (!$page || !$page->access_token) {
                $post->update(['status'=>'failed','error'=>'missing token/page']);
                continue;
            }
            $endpoint = "https://graph.facebook.com/v19.0/{$page->page_id}";
            try {
                if ($post->type === 'photo' && $post->image_url) {
                    $resp = Http::asForm()->post($endpoint.'/photos', [
                        'url' => $post->image_url,
                        'caption' => $post->message,
                        'access_token' => $page->access_token,
                    ]);
                } else {
                    $payload = ['message'=>$post->message,'access_token'=>$page->access_token];
                    if ($post->link) $payload['link'] = $post->link;
                    $resp = Http::asForm()->post($endpoint.'/feed', $payload);
                }
                if ($resp->ok()) {
                    $post->update(['status'=>'published','fb_post_id'=>$resp->json('id') ?? $resp->json('post_id')]);
                    $this->info("Published post #{$post->id}");
                } else {
                    $post->update(['status'=>'failed','error'=>$resp->body()]);
                    $this->error("Failed #{$post->id}: {$resp->body()}");
                }
            } catch (\Throwable $e) {
                $post->update(['status'=>'failed','error'=>$e->getMessage()]);
                $this->error("Error #{$post->id}: {$e->getMessage()}");
            }
        }
        return 0;
    }
}
