<?php
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureFacebookAuthenticated as FbAuth;
use App\Http\Controllers\FbSessionController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;

Route::get('/', fn() => view('welcome_fb'));
Route::get('/connect', [FbSessionController::class, 'connect'])->name('connect');
Route::post('/auth/facebook/bind', [FbSessionController::class, 'bind'])->name('fb.bind');
Route::get('/logout', [FbSessionController::class, 'logout'])->name('logout');

Route::middleware([FbAuth::class])->group(function () {
    Route::get('/me', [AccountController::class, 'me'])->name('me');
    Route::post('/me/sync-pages', [AccountController::class, 'syncPages'])->name('me.sync_pages');
    Route::get('/pages', [PageController::class, 'index'])->name('pages');
    Route::get('/pages/{page}/posts', [PostController::class, 'index'])->name('pages.posts');
    Route::post('/pages/{page}/posts', [PostController::class, 'store'])->name('pages.posts.store');
    Route::post('/pages/{page}/posts/{post}/publish', [PostController::class, 'publish'])->name('pages.posts.publish');
});
