<?php
namespace App\Http\Controllers;
use App\Models\FacebookPage;
use App\Models\PageMembership;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class PostController extends Controller
{
  public function index(Request $r, FacebookPage $page){
    $accountId = $r->session()->get('fb_account_id');
    $membership = PageMembership::where([
      'facebook_account_id'=>$accountId,'facebook_page_id'=>$page->id,'is_active'=>true
    ])->first();
    if (!$membership) return redirect()->route('pages')->with('error','Bạn không có quyền với Page này.');
    $posts = $page->posts()->orderByDesc('scheduled_at')->orderByDesc('id')->paginate(15);
    return view('posts.index', compact('page','posts','membership'));
  }
  public function store(Request $r, FacebookPage $page){
    $accountId = $r->session()->get('fb_account_id');
    $membership = PageMembership::where([
      'facebook_account_id'=>$accountId,'facebook_page_id'=>$page->id,'is_active'=>true
    ])->first();
    if (!$membership) return back()->with('error','Thiếu quyền.');
    $d = $r->validate([
      'type'=>'required|in:text,photo,link','message'=>'nullable|string','link'=>'nullable|url',
      'image_url'=>'nullable|url','scheduled_at'=>'nullable|date','action'=>'nullable|string'
    ]);
    $status='draft'; 
    if (($d['action'] ?? '')==='publish_now') $status='publishing';
    elif (!empty($d['scheduled_at'])) $status='scheduled';
    $post = $page->posts()->create([
      'page_membership_id'=>$membership->id,'type'=>$d['type'],'message'=>$d['message'] ?? null,
      'link'=>$d['link'] ?? null,'image_url'=>$d['image_url'] ?? null,'scheduled_at'=>$d['scheduled_at'] ?? null,'status'=>$status,
    ]);
    if ($status==='publishing') return $this->publishNow($membership,$page,$post);
    return redirect()->route('pages.posts',$page)->with('ok','Đã lưu bài viết.');
  }
  public function publish(Request $r, FacebookPage $page, Post $post){
    $accountId = $r->session()->get('fb_account_id');
    $membership = PageMembership::where([
      'facebook_account_id'=>$accountId,'facebook_page_id'=>$page->id,'is_active'=>true
    ])->first();
    if (!$membership) return back()->with('error','Thiếu quyền.');
    return $this->publishNow($membership,$page,$post);
  }
  private function publishNow(PageMembership $membership, FacebookPage $page, Post $post){
    $token=$membership->page_access_token; if (!$token) return back()->with('error','Thiếu page access token.');
    $endpoint="https://graph.facebook.com/v19.0/{$page->page_id}";
    try{
      if ($post->type==='photo' && $post->image_url){
        $resp=Http::asForm()->post($endpoint.'/photos',['url'=>$post->image_url,'caption'=>$post->message,'access_token'=>$token]);
      } else {
        $payload=['message'=>$post->message,'access_token'=>$token]; if ($post->link) $payload['link']=$post->link;
        $resp=Http::asForm()->post($endpoint.'/feed',$payload);
      }
      if ($resp->ok()){
        $id=$resp->json('id') ?? $resp->json('post_id'); $post->update(['status'=>'published','fb_post_id'=>$id,'error'=>null]);
        return redirect()->route('pages.posts',$page)->with('ok','Đăng thành công.');
      }
      $post->update(['status'=>'failed','error'=>$resp->body()]);
      return redirect()->route('pages.posts',$page)->with('error','Đăng thất bại: '.$resp->body());
    }catch(\Throwable $e){
      $post->update(['status'=>'failed','error'=>$e->getMessage()]);
      return redirect()->route('pages.posts',$page)->with('error','Đăng lỗi: '.$e->getMessage());
    }
  }
}