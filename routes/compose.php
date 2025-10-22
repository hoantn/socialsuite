<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComposeController;

Route::get('/compose', [ComposeController::class, 'form'])->name('compose.form');
Route::post('/compose', [ComposeController::class, 'publish'])->name('compose.publish');
