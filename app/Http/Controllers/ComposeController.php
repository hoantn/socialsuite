<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\FbUser;
use App\Models\FbPage;
use App\Models\FbPageToken;
use App\Models\FbPost;

class ComposeController extends Controller
{
    protected function graphV(): string
    {
        return config('socialsuite.facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v20.0'));
    }

    public function form(Request $request)
    {
        $uid = session('fb_uid');
        $user = $uid ? FbUser::find($uid) : null;
        $pages = $user ? FbPage::where('owner_id', $user->id)->orderBy('name')->get() : collect();
        return view('compose.index', compact('pages', 'user'));
    }

    public function publish(Request $request)
    {
        $request->validate([
            'message' => 'nullable|string|max:63206',
            'photo'   => 'nullable|file|mimes:jpg,jpeg,png,gif|max:5120',
            'pages'   => 'required|array|min:1',
        ],[
            'pages.required' => 'Hãy chọn ít nhất một Page.'
        ]);

        $message = $request->input('message');
        $pageIds = $request->input('pages', []);

        $okCount = 0; $failCount = 0;

        foreach ($pageIds as $pid) {
            $page = FbPage::where('page_id', $pid)->first();
            $token = FbPageToken::where('page_id', $pid)->value('access_token');
            if (!$page || !$token) { $failCount++; continue; }

            try {
                if ($request->hasFile('photo')) {
                    $file = $request->file('photo');
                    $resp = Http::attach('source', file_get_contents($file->getRealPath()), $file->getClientOriginalName())
                        ->asMultipart()
                        ->post('https://graph.facebook.com/'.$this->graphV()."/{$pid}/photos", [
                            'caption' => $message,
                            'access_token' => $token,
                        ]);
                    $type = 'photo';
                } else {
                    $resp = Http::asForm()->post('https://graph.facebook.com/'.$this->graphV()."/{$pid}/feed", [
                        'message' => $message,
                        'access_token' => $token,
                    ]);
                    $type = 'feed';
                }

                $ok = $resp->ok();
                $data = $resp->json();

                FbPost::create([
                    'page_id' => $pid,
                    'page_name' => $page->name ?? null,
                    'post_id' => $data['id'] ?? ($data['post_id'] ?? null),
                    'message' => $message,
                    'type'    => $type,
                    'status'  => $ok ? 'published' : 'error',
                    'error_code' => $ok ? null : (data_get($data,'error.code')),
                    'error_message' => $ok ? null : (data_get($data,'error.message')),
                    'response'=> $data,
                ]);

                if ($ok) $okCount++; else $failCount++;

            } catch (\Throwable $e) {
                Log::error('Publish error (multi): '.$e->getMessage(), ['page_id'=>$pid]);
                FbPost::create([
                    'page_id' => $pid,
                    'page_name' => $page->name ?? null,
                    'post_id' => null,
                    'message' => $message,
                    'type'    => isset($file) ? 'photo' : 'feed',
                    'status'  => 'error',
                    'error_message' => $e->getMessage(),
                    'response'=> ['exception'=>$e->getMessage()],
                ]);
                $failCount++;
            }
        }

        return back()->with('status', "Kết quả: thành công {$okCount}, thất bại {$failCount}.");
    }
}
