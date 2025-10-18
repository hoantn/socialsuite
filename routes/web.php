<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FacebookAuthController;

Route::get('/', fn() => response('SocialSuite Rebuild Starter'));

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login',    [AuthController::class, 'login'])->name('login');
Route::post('/logout',   [AuthController::class, 'logout'])->name('logout');

Route::get('/auth/facebook/login',    [FacebookAuthController::class, 'login'])->name('fb.login');
Route::get('/auth/facebook/callback', [FacebookAuthController::class, 'callback'])->name('fb.callback');
