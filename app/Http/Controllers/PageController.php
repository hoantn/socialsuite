<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\FacebookPage;

class PageController extends Controller
{
    public function index(Request $r){
        $aid = $r->session()->get('fb_account_id');
        $pages = FacebookPage::select('facebook_pages.*')
            ->join('page_memberships as m','m.facebook_page_id','=','facebook_pages.id')
            ->where('m.facebook_account_id',$aid)->where('m.is_active',true)
            ->orderBy('name')->paginate(20);
        return view('pages/index', compact('pages'));
    }
}
