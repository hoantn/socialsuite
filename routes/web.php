
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\WebhookController;

Route::get('/', fn () => view('app'))->name('spa');

Route::get('/auth/facebook/redirect', [OAuthController::class,'redirect'])->name('facebook.redirect');
Route::get('/auth/facebook/callback', [OAuthController::class,'callback'])->name('facebook.callback');

Route::prefix('api')->group(function () {
    Route::get('/pages', [PageController::class,'list']);
    Route::post('/facebook/import-pages', [PageController::class,'importAndSubscribe']);
});

Route::match(['get','post'], '/webhooks/facebook', [WebhookController::class,'handle']);
