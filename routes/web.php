<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\{PageController,InboxController,BroadcastController,FlowController,SettingsController,WebhookController,FacebookController,OAuthController};

Route::get('/', fn () => Inertia::render('Dashboard'));
Route::get('/pages', [PageController::class, 'index'])->name('pages.index');
Route::post('/pages', [PageController::class, 'store'])->name('pages.store');
Route::delete('/pages/{page}', [PageController::class, 'destroy'])->name('pages.destroy');

// OAuth + import pages
Route::get('/auth/facebook/redirect', [OAuthController::class, 'redirect'])->name('facebook.redirect');
Route::get('/auth/facebook/callback', [OAuthController::class, 'callback'])->name('facebook.callback');
Route::post('/pages/import', [OAuthController::class, 'importPages'])->name('pages.import');

Route::get('/inbox', [InboxController::class, 'index'])->name('inbox.index');
Route::post('/inbox/send', [InboxController::class, 'send'])->name('inbox.send');

Route::get('/broadcasts', [BroadcastController::class, 'index'])->name('broadcasts.index');
Route::post('/broadcasts', [BroadcastController::class, 'store'])->name('broadcasts.store');

Route::get('/flows', [FlowController::class, 'index'])->name('flows.index');
Route::post('/flows', [FlowController::class, 'store'])->name('flows.store');

Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');

// Webhook + APIs
Route::match(['get','post'], '/webhook/facebook', [WebhookController::class, 'handle'])->name('facebook.webhook');
Route::post('/api/facebook/send', [FacebookController::class, 'send'])->name('facebook.api.send');
Route::post('/api/facebook/broadcast', [FacebookController::class, 'broadcast'])->name('facebook.api.broadcast');