<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Page;
class DashboardController extends Controller
{
    public function welcome(){ return view('dashboard.welcome'); }
    public function index(){
        $user=Auth::user(); $pages=Page::where('user_id',$user->id)->get();
        $postsCount=Post::where('user_id',$user->id)->count();
        $scheduledCount=Post::where('user_id',$user->id)->where('status','scheduled')->count();
        return view('dashboard.index',compact('user','pages','postsCount','scheduledCount'));
    }
}
