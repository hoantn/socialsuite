<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\FbUser;
use App\Models\FbPage;
use App\Models\FbPageToken;
use App\Models\FbPost;

class PageController extends Controller
{
    protected function graphV(): string
    {
        return config('socialsuite.facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v20.0'));
    }

    // danh sách page
    public function index(Request $request)
    {
        $uid = session('fb_uid');
        $user = $uid ? FbUser::find($uid) : null;
        $pages = $user ? FbPage::where('owner_id', $user->id)->orderBy('name')->get() : collect();
        return view('pages.index', compact('pages', 'user'));
    }

    // trang quản lý 1 page
    public function show(Request $request, string $pageId)
    {
        $page = FbPage::where('page_id', $pageId)->firstOrFail();
        $token = FbPageToken::where('page_id', $pageId)->value('access_token');

        // recent posts
        $recent = [];
        if ($token) {
            $resp = Http::get('https://graph.facebook.com/'.$this->graphV()."/{$pageId}/published_posts", [
                'fields' => 'id,message,created_time,permalink_url',
                'limit' => 10,
                'access_token' => $token,
            ]);
            if ($resp->ok()) {
                $recent = $resp->json()['data'] ?? [];
            } else {
                Log::warning('Fetch recent posts failed', ['body' => $resp->body()]);
            }
        }

        return view('pages.show', [
            'page' => $page,
            'pageToken' => $token,
            'recent' => $recent,
        ]);
    }

    // publish post
    public function publish(Request $request, string $pageId)
    {
        $request->validate([
            'message' => 'nullable|string|max:63206',
            'photo'   => 'nullable|file|mimes:jpg,jpeg,png,gif|max:5120',
        ]);

        $page = FbPage::where('page_id', $pageId)->firstOrFail();
        $token = FbPageToken::where('page_id', $pageId)->value('access_token');

        if (!$token) {
            return back()->with('status', 'Không tìm thấy token của page.');
        }

        $message = $request->input('message');

        try {
            if ($request->hasFile('photo')) {
                // Photo post
                $file = $request->file('photo');
                $resp = Http::attach('source', file_get_contents($file->getRealPath()), $file->getClientOriginalName())
                    ->asMultipart()
                    ->post('https://graph.facebook.com/'.$this->graphV()."/{$pageId}/photos", [
                        'caption' => $message,
                        'access_token' => $token,
                    ]);

                $type = 'photo';
            } else {
                // Text post
                $resp = Http::asForm()->post('https://graph.facebook.com/'.$this->graphV()."/{$pageId}/feed", [
                    'message' => $message,
                    'access_token' => $token,
                ]);
                $type = 'feed';
            }

            $ok = $resp->ok();
            $data = $resp->json();

            FbPost::create([
                'page_id' => $pageId,
                'post_id' => $data['id'] ?? ($data['post_id'] ?? null),
                'message' => $message,
                'type'    => $type,
                'status'  => $ok ? 'published' : 'error',
                'response'=> $data,
            ]);

            if (!$ok) {
                return back()->with('status', 'Đăng bài thất bại: '.($data['error']['message'] ?? 'Unknown error'));
            }

            return back()->with('status', 'Đăng bài thành công!');

        } catch (\Throwable $e) {
            Log::error('Publish error: '.$e->getMessage());
            FbPost::create([
                'page_id' => $pageId,
                'post_id' => null,
                'message' => $message,
                'type'    => isset($file) ? 'photo' : 'feed',
                'status'  => 'error',
                'response'=> ['exception' => $e->getMessage()],
            ]);
            return back()->with('status', 'Đăng bài lỗi: '.$e->getMessage());
        }
    }
}
