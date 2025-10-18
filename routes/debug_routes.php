<?php
use Illuminate\Support\Facades\Route;

Route::get('/debug/me-accounts', function () {
    $rec = \App\Models\FacebookToken::latest('id')->first();
    abort_unless($rec, 404, 'No token');
    $r = \Illuminate\Support\Facades\Http::get('https://graph.facebook.com/v19.0/me/accounts', [
        'fields' => 'id,name,access_token',
        'access_token' => $rec->token,
    ]);
    return response($r->body(), $r->status())->header('Content-Type','application/json');
});

Route::get('/debug/pages', function () {
    $pages = \App\Models\FacebookPage::orderBy('id','desc')->get(['fb_user_id','page_id','name']);
    return response()->json($pages);
});
