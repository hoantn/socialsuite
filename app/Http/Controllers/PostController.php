<?php
namespace App\Http\Controllers;
use App\Models\FacebookPage;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class PostController extends Controller
{
    // List posts for a page
    public function index(FacebookPage $page)
    {
        $posts = $page->posts()->orderByDesc('scheduled_at')->orderByDesc('id')->paginate(15);
        return view('posts.index', compact('page','posts'));
    }
    // Store a draft/scheduled post
    public function store(Request $r, FacebookPage $page)
    {
        $d = $r->validate([
            'type'        => 'required|in:text,photo,link',
            'message'     => 'nullable|string',
            'link'        => 'nullable|url',
            'image_url'   => 'nullable|url',
            'scheduled_at'=> 'nullable|date',
            'action'      => 'nullable|string', // publish_now|save|schedule
        ]);
        $status = 'draft';
        if (($d['action'] ?? '') === 'publish_now') $status = 'publishing';
        elseif (!empty($d['scheduled_at'])) $status = 'scheduled';
        $post = $page->posts()->create([
            'type' => $d['type'],
            'message' => $d['message'] ?? null,
            'link' => $d['link'] ?? null,
            'image_url' => $d['image_url'] ?? null,
            'scheduled_at' => $d['scheduled_at'] ?? null,
            'status' => $status,
        ]);
        if ($status === 'publishing') {
            return $this->publishNow($page, $post);
        }
        return redirect()->route('pages.posts', $page)->with('ok', 'Đã lưu bài viết.');
    }
    public function publish(FacebookPage $page, Post $post)
    {
        return $this->publishNow($page, $post);
    }
    private function publishNow(FacebookPage $page, Post $post)
    {
        $token = $page->access_token;
        if (!$token) return back()->with('error','Thiếu page access token.');
        $endpoint = "https://graph.facebook.com/v19.0/{$page->page_id}";
        $resp = null;
        try {
            if ($post->type === 'photo' && $post->image_url) {
                $resp = Http::asForm()->post($endpoint.'/photos', [
                    'url' => $post->image_url,
                    'caption' => $post->message,
                    'access_token' => $token,
                ]);
            } else {
                $payload = [
                    'message' => $post->message,
                    'access_token' => $token,
                ];
                if ($post->link) $payload['link'] = $post->link;
                $resp = Http::asForm()->post($endpoint.'/feed', $payload);
            }
            if ($resp && $resp->ok()) {
                $id = $resp->json('id') ?? $resp->json('post_id') ?? null;
                $post->update(['status' => 'published', 'fb_post_id' => $id, 'error' => null]);
                return redirect()->route('pages.posts',$page)->with('ok','Đăng thành công.');
            } else {
                $post->update(['status' => 'failed', 'error' => optional($resp)->body()]);
                return redirect()->route('pages.posts',$page)->with('error','Đăng thất bại: '.optional($resp)->body());
            }
        } catch (\Throwable $e) {
            $post->update(['status' => 'failed', 'error' => $e->getMessage()]);
            return redirect()->route('pages.posts',$page)->with('error','Đăng lỗi: '.$e->getMessage());
        }
    }
}
