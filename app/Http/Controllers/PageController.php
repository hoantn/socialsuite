<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Facebook\Facebook;
use App\Models\{FbAccount,FbPage,AccountPage};
use Illuminate\Http\Request;

class PageController extends Controller {
    public function index() {
        $pages = FbPage::query()->orderBy('name')->get();
        return view('pages.index', compact('pages'));
    }

    public function sync(Facebook $fb) {
        $acc = FbAccount::findOrFail(session('fb_account_id'));
        $fb->setDefaultAccessToken($acc->user_access_token);

        $resp = $fb->get('/me/accounts?fields=id,name,category,picture{url},username,connected_instagram_account,access_token,perms');
        $edges = $resp->getGraphEdge();

        DB::transaction(function () use ($edges, $acc) {
            foreach ($edges as $p) {
                $pageId = (string)$p['id'];
                $pageToken = (string)$p['access_token'];
                FbPage::updateOrCreate(
                    ['page_id' => $pageId],
                    [
                        'name' => $p['name'] ?? null,
                        'username' => $p['username'] ?? null,
                        'category' => $p['category'] ?? null,
                        'avatar_url' => $p['picture']['url'] ?? null,
                        'connected_ig_id' => $p['connected_instagram_account']['id'] ?? null,
                        'page_access_token' => $pageToken,
                        'capabilities' => $p['perms'] ?? [],
                    ]
                );
                AccountPage::updateOrCreate(
                    ['fb_account_id'=>$acc->id, 'page_id'=>$pageId],
                    ['granted_scopes'=>$p['perms'] ?? [], 'role'=>null]
                );
            }
        });

        return redirect()->route('pages.index')->with('ok','Đã đồng bộ Page từ Facebook.');
    }
}
