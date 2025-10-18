<?php
namespace App\Http\Controllers;
use App\Models\FacebookPage;
use App\Models\FacebookToken; // assumes you have this per earlier pack
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class PageController extends Controller
{
    // [SOCIALSUITE][GPT][2025-10-18 10:44 +07] List pages from DB
    public function index()
    {
        $pages = FacebookPage::orderBy('name')->paginate(20);
        return view('pages.index', compact('pages'));
    }
    // Sync from Graph me/accounts using latest user token
    public function sync()
    {
        $token = optional(FacebookToken::latest('id')->first())->token;
        if (!$token) return back()->with('error','Không tìm thấy user token. Hãy kết nối Facebook trước.');
        $resp = Http::get('https://graph.facebook.com/v19.0/me/accounts', [
            'fields' => 'id,name,access_token',
            'access_token' => $token,
            'limit' => 100,
        ]);
        if (!$resp->ok()) {
            return back()->with('error', 'Graph error: '.$resp->body());
        }
        $added = 0;
        foreach ((array)$resp->json('data') as $p) {
            $rec = FacebookPage::updateOrCreate(
                ['page_id' => $p['id']],
                [
                    'fb_user_id' => 'me',
                    'name' => $p['name'] ?? 'Page',
                    'access_token' => $p['access_token'] ?? null,
                ]
            );
            $added++;
        }
        return back()->with('ok', "Đã đồng bộ {$added} trang.");
    }
}
