
<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\SocialAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PageController extends Controller
{
    public function list()
    {
        return Page::query()->orderByDesc('id')->get();
    }

    public function importAndSubscribe(Request $req)
    {
        $user = Auth::user() ?? \App\Models\User::first();
        if (!$user) { abort(401, 'No user'); }

        $account = SocialAccount::where('user_id',$user->id)
            ->where('provider','facebook')->first();

        if (!$account) abort(400, 'Facebook not connected');

        $resp = Http::get('https://graph.facebook.com/v20.0/me/accounts', [
            'access_token' => $account->access_token,
            'fields' => 'id,name,category,access_token',
            'limit'  => 100,
        ])->throw()->json();

        $selected = collect($req->input('select', []))->map(fn($v)=> (string)$v);
        $imported = [];

        foreach ((array) data_get($resp, 'data', []) as $p) {
            $pid = (string) data_get($p,'id');
            if ($selected->isNotEmpty() && !$selected->contains($pid)) continue;

            $page = Page::updateOrCreate(
                ['provider_page_id'=>$pid],
                [
                    'name' => data_get($p,'name'),
                    'category' => data_get($p,'category'),
                    'page_access_token' => data_get($p,'access_token'),
                ]
            );

            $page->users()->syncWithoutDetaching([$user->id]);

            try {
                Http::asForm()->post("https://graph.facebook.com/v20.0/{$pid}/subscribed_apps", [
                    'access_token'      => $page->page_access_token,
                    'subscribed_fields' => 'messages,messaging_postbacks,message_deliveries,message_reads,feed',
                ])->throw();

                $page->update(['subscribed'=>true]);
            } catch (\Throwable $e) {
                report($e);
            }

            $imported[] = $page->refresh();
        }

        return response()->json([
            'ok' => true,
            'count' => count($imported),
            'pages' => $imported,
        ]);
    }
}
