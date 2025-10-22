<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\FacebookController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Phase 2: OAuth (stub)
// Hiện tại chỉ tạo route để UI không 404.
Route::get('/auth/facebook', [FacebookController::class, 'redirect'])->name('facebook.redirect');
Route::get('/auth/facebook/callback', [FacebookController::class, 'callback'])->name('facebook.callback');

Route::middleware('web')->group(function () {
    Route::get('/dashboard', [FacebookController::class, 'dashboard'])->name('dashboard');
});
