<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController, PageController, PageConfigController, WebhookController, HealthController};
use App\Http\Middleware\{FbAuth, EnsureHasPageAccess};

Route::get('/', function () { return view('welcome'); })->name('home');

// Auth
Route::get('/auth/facebook/redirect', [AuthController::class,'redirect'])->name('fb.redirect');
Route::get('/auth/facebook/callback', [AuthController::class,'callback'])->name('fb.callback');
Route::post('/logout', [AuthController::class,'logout'])->name('logout');

// Protected
Route::middleware([FbAuth::class])->group(function () {
    Route::get('/pages', [PageController::class,'index'])->name('pages.index');
    Route::post('/pages/sync', [PageController::class,'sync'])->name('pages.sync');

    Route::middleware([EnsureHasPageAccess::class])->group(function () {
        Route::get('/pages/{page_id}/settings', [PageConfigController::class,'edit'])->name('pages.settings');
        Route::post('/pages/{page_id}/settings', [PageConfigController::class,'update'])->name('pages.settings.update');
    });
});

// Webhooks
Route::get('/webhooks/facebook', [WebhookController::class,'handleGet']);
Route::post('/webhooks/facebook', [WebhookController::class,'handlePost']);

// Health & Privacy
Route::get('/health', [HealthController::class,'index'])->name('health');
Route::view('/privacy/data-deletion','privacy.data_deletion')->name('privacy.data_deletion');
