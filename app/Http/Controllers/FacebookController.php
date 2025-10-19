<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request; use App\Services\FacebookService;
class FacebookController extends Controller {
  public function __construct(private FacebookService $fb) {}
  public function send(Request $r){
    $data = $r->validate(['psid'=>'required','text'=>'required']);
    [$status, $json] = $this->fb->sendMessage(env('META_PAGE_ACCESS_TOKEN',''), $data['psid'], $data['text']);
    return response()->json(['status'=>$status,'response'=>$json], $status >=200 && $status<300 ? 200 : 500);
  }
}