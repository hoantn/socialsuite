<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Facebook\Facebook;
use App\Models\Page;
use App\Models\Post as PostModel;
class PostController extends Controller
{
    private function fb(): Facebook {
        return new Facebook(['app_id'=>config('services.facebook.client_id'),
            'app_secret'=>config('services.facebook.client_secret'),'default_graph_version'=>'v19.0']);
    }
    private function activePage(){
        $pid=session('active_page_id'); if(!$pid) return null;
        return Page::where('user_id',Auth::id())->where('page_id',$pid)->first();
    }
    public function index(){
        $page=$this->activePage(); $posts=[]; $error=null;
        if($page){ try{
            $resp=$this->fb()->get("/{$page->page_id}/feed?fields=id,message,created_time,permalink_url", $page->page_token);
            $posts=json_decode($resp->getBody(),true)['data']??[];
        }catch(\Throwable $e){ $error=$e->getMessage(); } }
        return view('posts.index',compact('page','posts','error'));
    }
    public function create(){ $page=$this->activePage(); return view('posts.create',compact('page')); }
    public function store(Request $r){
        $r->validate(['message'=>'required|string|max:5000','schedule_at'=>'nullable|date']);
        $page=$this->activePage(); if(!$page) return back()->withErrors(['page'=>'Bạn chưa chọn Page'])->withInput();
        $args=['message'=>$r->message]; $endpoint="/{$page->page_id}/feed";
        if($r->filled('schedule_at')){
            $ts=strtotime($r->schedule_at); if($ts<time()+600)
                return back()->withErrors(['schedule_at'=>'Thời gian hẹn phải >= 10 phút'])->withInput();
            $args['scheduled_publish_time']=$ts; $args['published']=false;
        }
        try{
            $resp=$this->fb()->post($endpoint,$args,$page->page_token);
            $id=json_decode($resp->getBody(),true)['id']??null;
            PostModel::create(['user_id'=>Auth::id(),'page_id'=>$page->id,'fb_post_id'=>$id,
                'message'=>$r->message,'status'=>$r->filled('schedule_at')?'scheduled':'published',
                'scheduled_at'=>$r->filled('schedule_at')?date('Y-m-d H:i:s',$ts):null]);
            return redirect()->route('posts.index')->with('ok',$r->filled('schedule_at')?'Đã lên lịch bài viết':'Đã đăng bài viết');
        }catch(\Throwable $e){ return back()->withErrors(['error'=>$e->getMessage()])->withInput(); }
    }
    public function scheduled(){
        $page=$this->activePage();
        $items=PostModel::where('user_id',Auth::id())->where('status','scheduled')->orderBy('scheduled_at','asc')->get();
        return view('posts.scheduled',compact('page','items'));
    }
    public function destroy($postId){
        $page=$this->activePage(); if(!$page) return back();
        try{ $this->fb()->delete("/{$postId}",[],$page->page_token); }catch(\Throwable $e){}
        PostModel::where('fb_post_id',$postId)->delete(); return back()->with('ok','Đã xoá bài');
    }
}
