<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FacebookAuthController;

// Homepage + sitemap
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/sitemap.xml', [HomeController::class, 'sitemap'])->name('sitemap');

// Auth forms (UI)
Route::view('/login', 'auth.login')->name('login.form');
Route::view('/register', 'auth.register')->name('register.form');

// Auth actions (already implemented in AuthController)
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/register', [AuthController::class, 'register'])->name('register');

// Facebook OAuth (manual exchange)
Route::get('/auth/facebook/login',    [FacebookAuthController::class, 'login'])->name('fb.login');
Route::get('/auth/facebook/callback', [FacebookAuthController::class, 'callback'])->name('fb.callback');

// (Optional) Debug routes include if the file exists
if (file_exists(__DIR__ . '/debug_routes.php')) {
    require __DIR__ . '/debug_routes.php';
}
require __DIR__.'/pages_posts.php';
