<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\FacebookPage;
use App\Models\PageMembership;
use App\Models\Post;

class PostController extends Controller
{
    public function index(Request $r, FacebookPage $page){
        $aid = $r->session()->get('fb_account_id');
        $membership = PageMembership::where([
            'facebook_account_id'=>$aid,'facebook_page_id'=>$page->id,'is_active'=>true
        ])->first();
        if (!$membership) return redirect()->route('pages')->with('error','Bạn không có quyền với Page này.');
        $posts = $page->posts()->orderByDesc('id')->paginate(15);
        return view('posts/index', compact('page','posts','membership'));
    }
    public function store(Request $r, FacebookPage $page){
        $aid = $r->session()->get('fb_account_id');
        $membership = PageMembership::where([
            'facebook_account_id'=>$aid,'facebook_page_id'=>$page->id,'is_active'=>true
        ])->first();
        if (!$membership) return back()->with('error','Thiếu quyền.');
        $d = $r->validate([
            'type'=>'required|in:text,photo,link','message'=>'nullable|string',
            'link'=>'nullable|url','image_url'=>'nullable|url','action'=>'nullable|string'
        ]);
        $status = ($r->input('action')==='publish_now') ? 'publishing' : 'draft';
        $post = $page->posts()->create([
            'page_membership_id'=>$membership->id,'type'=>$d['type'],
            'message'=>$d['message'] ?? null,'link'=>$d['link'] ?? null,
            'image_url'=>$d['image_url'] ?? null,'status'=>$status
        ]);
        if ($status==='publishing') return $this->publishNow($membership,$page,$post);
        return back()->with('ok','Đã lưu.');
    }
    public function publish(Request $r, FacebookPage $page, Post $post){
        $aid = $r->session()->get('fb_account_id');
        $membership = PageMembership::where([
            'facebook_account_id'=>$aid,'facebook_page_id'=>$page->id,'is_active'=>true
        ])->first();
        if (!$membership) return back()->with('error','Thiếu quyền.');
        return $this->publishNow($membership,$page,$post);
    }
    private function publishNow(PageMembership $membership, FacebookPage $page, Post $post){
        $token = $membership->page_access_token;
        if (!$token) return back()->with('error','Thiếu page access token.');
        $endpoint = "https://graph.facebook.com/v19.0/{$page->page_id}";
        if ($post->type==='photo' && $post->image_url) {
            $resp = Http::asForm()->post($endpoint.'/photos',[
                'url'=>$post->image_url,'caption'=>$post->message,'access_token'=>$token
            ]);
        } else {
            $payload=['message'=>$post->message,'access_token'=>$token];
            if ($post->link) $payload['link']=$post->link;
            $resp = Http::asForm()->post($endpoint.'/feed',$payload);
        }
        if ($resp->ok()) {
            $id = $resp->json('id') ?? $resp->json('post_id');
            $post->update(['status'=>'published','fb_post_id'=>$id,'error'=>null]);
            return back()->with('ok','Đăng thành công.');
        }
        $post->update(['status'=>'failed','error'=>$resp->body()]);
        return back()->with('error','Đăng thất bại.');
    }
}
