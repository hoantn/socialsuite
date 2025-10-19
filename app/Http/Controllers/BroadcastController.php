<?php
namespace App\Http\Controllers;
use App\Models\Campaign; use Inertia\Inertia; use Illuminate\Http\Request;
class BroadcastController extends Controller {
  public function index(){ return Inertia::render('Broadcasts/Index',['items'=>Campaign::orderByDesc('id')->get()]); }
  public function store(Request $r){ $d=$r->validate(['page_id'=>'required','name'=>'required','content'=>'required']); $d['status']='scheduled'; Campaign::create($d); return back()->with('success','Campaign created'); }
}