<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FacebookAuthController;

Route::get('/health', fn() => response()->json(['ok'=>true]));
Route::prefix('facebook')->group(function(){
    Route::get('/oauth-url', [FacebookAuthController::class, 'oauthUrl']);
    Route::get('/callback', [FacebookAuthController::class, 'callback']);
});
Route::get('/pages', [FacebookAuthController::class, 'listPages']);
Route::post('/pages/{page}/subscribe', [FacebookAuthController::class, 'subscribeWebhook']);
