<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\FacebookManualController as FB;

// Home
Route::get('/', [FB::class, 'home'])->name('home');

// OAuth
Route::get('/auth/facebook', [FB::class, 'redirect'])->name('facebook.redirect');
Route::get('/auth/facebook/callback', [FB::class, 'callback'])->name('facebook.callback');
Route::get('/logout', [FB::class, 'logout'])->name('logout');

// Dashboard
Route::get('/dashboard', [FB::class, 'dashboard'])->name('dashboard');
