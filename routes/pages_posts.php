<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
Route::middleware(['web','auth'])->group(function () {
    Route::get('/pages', [PageController::class, 'index'])->name('pages');
    Route::post('/pages/sync', [PageController::class, 'sync'])->name('pages.sync');
    Route::get('/pages/{page}/posts', [PostController::class, 'index'])->name('pages.posts');
    Route::post('/pages/{page}/posts', [PostController::class, 'store'])->name('pages.posts.store');
    Route::post('/pages/{page}/posts/{post}/publish', [PostController::class, 'publish'])->name('pages.posts.publish');
    Route::get('/me', [ProfileController::class, 'show'])->name('me');
    Route::post('/me', [ProfileController::class, 'update'])->name('me.update');
});
