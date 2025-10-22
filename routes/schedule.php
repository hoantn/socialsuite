<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScheduledPostController;

Route::get('/schedule', [ScheduledPostController::class, 'index'])->name('schedule.index');
Route::post('/schedule', [ScheduledPostController::class, 'store'])->name('schedule.store');
Route::post('/schedule/{id}/cancel', [ScheduledPostController::class, 'cancel'])->name('schedule.cancel');
