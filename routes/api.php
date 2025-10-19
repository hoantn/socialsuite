<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FacebookController;

// These are left unauthenticated for local dev. Add middleware('auth') when ready.
Route::get('/facebook/pages', [FacebookController::class, 'listPages']);
Route::post('/facebook/subscribe', [FacebookController::class, 'subscribe']);
