<?php
namespace App\Http\Controllers;
use App\Models\FacebookPage;
use App\Models\FacebookToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
class PageController extends Controller
{
    public function index()
    {
        $uid = Auth::id();
        $pages = FacebookPage::where('user_id', $uid)->orderBy('name')->paginate(20);
        return view('pages.index', compact('pages'));
    }
    public function sync()
    {
        $uid = Auth::id();
        if (!$uid) { return redirect()->route('login.form'); }
        $token = optional(FacebookToken::where('user_id',$uid)->latest('id')->first())->token
               ?? optional(FacebookToken::latest('id')->first())->token;
        if (!$token) return back()->with('error','Không tìm thấy user token. Hãy kết nối Facebook trước.');
        $resp = Http::get('https://graph.facebook.com/v19.0/me/accounts', [
            'fields' => 'id,name,access_token',
            'access_token' => $token,
            'limit' => 100,
        ]);
        if (!$resp->ok()) { return back()->with('error', 'Graph error: '.$resp->body()); }
        $added = 0;
        foreach ((array)$resp->json('data') as $p) {
            FacebookPage::updateOrCreate(
                ['page_id' => $p['id'], 'user_id' => $uid],
                [
                    'fb_user_id'   => 'me',
                    'name'         => $p['name'] ?? 'Page',
                    'access_token' => $p['access_token'] ?? null,
                ]
            );
            $added++;
        }
        return back()->with('ok', "Đã đồng bộ {$added} trang.");
    }
}
