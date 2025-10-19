<?php
namespace App\Http\Controllers;
use App\Models\Page; use Inertia\Inertia; use Illuminate\Http\Request; use Illuminate\Support\Facades\Auth;
class PageController extends Controller {
  public function index(){ $pages=Page::latest()->take(20)->get(['id','name','channel','page_id','avatar']); return Inertia::render('Pages/Index',['pages'=>$pages]); }
  public function store(Request $r){ $data=$r->validate(['name'=>'required','page_id'=>'required','access_token'=>'nullable']); $data['user_id']=Auth::id()??1; $data['channel']='messenger'; Page::create($data); return back()->with('success','Page added'); }
  public function destroy(Page $page){ $page->delete(); return back()->with('success','Page removed'); }
}