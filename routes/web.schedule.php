<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SchedulePostController;
Route::middleware(['web'])->group(function (){
  Route::get('/schedule', [SchedulePostController::class,'index'])->name('schedule.index');
  Route::post('/schedule', [SchedulePostController::class,'store'])->name('schedule.store');
  Route::post('/schedule/{id}/cancel', [SchedulePostController::class,'cancel'])->name('schedule.cancel');
});
