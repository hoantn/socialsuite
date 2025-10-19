<?php
namespace App\Http\Controllers;
use App\Models\{BotFlow,BotStep}; use Inertia\Inertia; use Illuminate\Http\Request;
class FlowController extends Controller {
  public function index(){ return Inertia::render('Flows/Index',['flows'=>BotFlow::with('steps')->get()]); }
  public function store(Request $r){ $d=$r->validate(['page_id'=>'required','name'=>'required']); $f=BotFlow::create($d+['is_active'=>true]); BotStep::create(['bot_flow_id'=>$f->id,'type'=>'text','payload'=>['text'=>'Xin chào!']]); return back()->with('success','Flow created'); }
}