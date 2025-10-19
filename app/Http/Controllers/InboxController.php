<?php
namespace App\Http\Controllers;
use App\Models\{Conversation,Message}; use Inertia\Inertia; use Illuminate\Http\Request;
class InboxController extends Controller {
  public function index(){ $conv=Conversation::orderByDesc('id')->limit(1)->get(); $messages=Message::orderBy('sent_at')->limit(10)->get();
    return Inertia::render('Inbox/Index',['conversations'=>$conv->map(fn($c)=>['id'=>$c->id,'status'=>$c->status,'subscriber'=>['name'=>'Demo'],'messages'=>$messages])]);
  }
  public function send(Request $r){ $r->validate(['conversation_id'=>'required','text'=>'required']); return back()->with('success','Message queued'); }
}