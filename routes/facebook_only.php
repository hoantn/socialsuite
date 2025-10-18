<?php
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureFacebookAuthenticated as FbAuth;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FbSessionController;
Route::post('/auth/facebook/bind',[FbSessionController::class,'bind'])->name('fb.bind');
Route::get('/auth/facebook/logout',[FbSessionController::class,'logout'])->name('fb.logout');
Route::middleware(['web', FbAuth::class])->group(function () {
  Route::get('/pages',[PageController::class,'index'])->name('pages');
  Route::post('/pages/sync',[PageController::class,'sync'])->name('pages.sync');
  Route::get('/pages/{page}/posts',[PostController::class,'index'])->name('pages.posts');
  Route::post('/pages/{page}/posts',[PostController::class,'store'])->name('pages.posts.store');
  Route::post('/pages/{page}/posts/{post}/publish',[PostController::class,'publish'])->name('pages.posts.publish');
});