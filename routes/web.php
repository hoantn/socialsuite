<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use App\Http\Controllers\{PageController,InboxController,BroadcastController,FlowController,SettingsController,WebhookController,FacebookController,OAuthController,PageActionsController};
use App\Services\MetaGraph;

Route::get('/', fn () => Inertia::render('Dashboard'));

Route::get('/pages', [PageController::class, 'index'])->name('pages.index');
Route::post('/pages', [PageController::class, 'store'])->name('pages.store');
Route::delete('/pages/{page}', [PageController::class, 'destroy'])->name('pages.destroy');

Route::get('/auth/facebook/redirect', [OAuthController::class, 'redirect'])->name('facebook.redirect');
Route::get('/auth/facebook/callback', [OAuthController::class, 'callback'])->name('facebook.callback');
Route::post('/pages/import', [OAuthController::class, 'importPages'])->name('pages.import');

Route::post('/pages/{page}/subscribe', [PageActionsController::class, 'subscribe'])->name('pages.subscribe');
Route::post('/pages/{page}/unsubscribe', [PageActionsController::class, 'unsubscribe'])->name('pages.unsubscribe');
Route::post('/pages/{page}/refresh-token', [PageActionsController::class, 'refreshToken'])->name('pages.refresh');

Route::get('/inbox', [InboxController::class, 'index'])->name('inbox.index');
Route::post('/inbox/send', [InboxController::class, 'send'])->name('inbox.send');

Route::get('/broadcasts', [BroadcastController::class, 'index'])->name('broadcasts.index');
Route::post('/broadcasts', [BroadcastController::class, 'store'])->name('broadcasts.store');

Route::get('/flows', [FlowController::class, 'index'])->name('flows.index');
Route::post('/flows', [FlowController::class, 'store'])->name('flows.store');

Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');

Route::match(['get','post'], '/webhook/facebook', [WebhookController::class, 'handle'])->name('facebook.webhook');
Route::post('/api/facebook/send', [FacebookController::class, 'send'])->name('facebook.api.send');
Route::post('/api/facebook/broadcast', [FacebookController::class, 'broadcast'])->name('facebook.api.broadcast');

// DEBUG: xem JSON trả về từ Graph /me/accounts
Route::get('/dev/fb/check', function (MetaGraph $graph) {
    $token = session('fb_user_token');
    if (!$token) return response()->json(['error'=>'no fb_user_token in session'], 400);
    try {
        $data = $graph->meAccounts($token);
        Log::info('DEV FB CHECK', $data);
        return $data;
    } catch (\Throwable $e) {
        Log::error('DEV FB CHECK ERROR', ['m'=>$e->getMessage()]);
        return response()->json(['error'=>$e->getMessage()], 500);
    }
})->name('dev.fb.check');