<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\FacebookManualController as FB;
use App\Http\Controllers\PageController;

// Home -> chuyển hướng tới /pages nếu đã login
Route::get('/', function () {
    return redirect()->route('pages.index');
})->name('home');

// OAuth
Route::get('/auth/facebook', [FB::class, 'redirect'])->name('facebook.redirect');
Route::get('/auth/facebook/callback', [FB::class, 'callback'])->name('facebook.callback');
Route::get('/logout', [FB::class, 'logout'])->name('logout');

// Pages
Route::get('/pages', [PageController::class, 'index'])->name('pages.index');
Route::get('/pages/{pageId}', [PageController::class, 'show'])->name('pages.show');
Route::post('/pages/{pageId}/publish', [PageController::class, 'publish'])->name('pages.publish');
Route::get('/dashboard', fn() => redirect()->route('pages.index'))->name('dashboard');
// nạp routes compose
if (file_exists(base_path('routes/compose.php'))) {
    require base_path('routes/compose.php');
}
// Nạp routes scheduler
if (file_exists(base_path('routes/schedule.php'))) {
    require base_path('routes/schedule.php');
}