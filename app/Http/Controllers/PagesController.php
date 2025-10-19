
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\FacebookUser;
use App\Models\FacebookPage;
use App\Models\FacebookPageUser;
use App\Models\FacebookPageWebhook;

class PagesController extends Controller
{
    public function index(Request $request)
    {
        $pages = FacebookPage::query()->orderBy('name')->get()->map(fn($p) => [
            'id' => $p->id,
            'page_id' => $p->page_id,
            'name' => $p->name,
        ]);

        return response()->json(['data' => $pages]);
    }

    public function import(Request $request)
    {
        $fbUser = FacebookUser::latest()->first();
        if (!$fbUser) {
            return response()->json(['message' => 'Chưa kết nối Facebook'], 400);
        }

        $pages = Http::get('https://graph.facebook.com/v19.0/me/accounts', [
            'access_token' => $fbUser->access_token,
            'fields' => 'id,name,access_token,category',
            'limit' => 100,
        ])->json('data') ?? [];

        foreach ($pages as $pg) {
            $page = FacebookPage::updateOrCreate(
                ['page_id' => $pg['id']],
                ['name' => $pg['name'] ?? null, 'category' => $pg['category'] ?? null, 'raw' => $pg]
            );

            FacebookPageUser::updateOrCreate(
                ['facebook_user_id' => $fbUser->id, 'facebook_page_id' => $page->id],
                ['page_access_token' => $pg['access_token'] ?? '']
            );

            // (Optional) subscribe webhook (needs verified app):
            // Http::post("https://graph.facebook.com/v19.0/{$pg['id']}/subscribed_apps", [
            //     'access_token' => $pg['access_token'],
            //     'subscribed_fields' => 'messages,messaging_postbacks'
            // ]);

            FacebookPageWebhook::updateOrCreate(
                ['facebook_page_id' => $page->id, 'subscription' => 'messages'],
                ['active' => true]
            );
        }

        return response()->json(['ok' => true]);
    }
}
