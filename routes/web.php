<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\FacebookManualController as FB;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ScheduledPostController;
use Laravel\Socialite\Facades\Socialite;

// Home -> chuyển hướng tới /pages nếu đã login
Route::get('/', function () {
    return redirect()->route('pages.index');
})->name('home');

// OAuth
Route::get('/auth/facebook', [FB::class, 'redirect'])->name('facebook.redirect');
Route::get('/auth/facebook/callback', [FB::class, 'callback'])->name('facebook.callback');
Route::get('/logout', [FB::class, 'logout'])->name('logout');

// Pages
Route::get('/pages', [PageController::class, 'index'])->name('pages.index');
Route::get('/pages/{pageId}', [PageController::class, 'show'])->name('pages.show');
Route::post('/pages/{pageId}/publish', [PageController::class, 'publish'])->name('pages.publish');
Route::get('/dashboard', fn() => redirect()->route('pages.index'))->name('dashboard');
// nạp routes compose
if (file_exists(base_path('routes/compose.php'))) {
    require base_path('routes/compose.php');
}
// Nạp routes scheduler
if (file_exists(base_path('routes/schedule.php'))) {
    require base_path('routes/schedule.php');
}
Route::get('/facebook/redirect', function () {
    return Socialite::driver('facebook')
        ->scopes([
            'email',
            'public_profile',
            'pages_show_list',
            'pages_manage_metadata',
            'pages_read_engagement',
            'pages_manage_posts',
            // thêm nếu cần: 'pages_read_user_content', ...
        ])
        ->redirect();
})->name('facebook.redirect');

Route::get('/facebook/callback', function () {
    $fbUser = Socialite::driver('facebook')->stateless()->user();

    // Tại đây bạn đã có:
    // $fbUser->getId(), $fbUser->getName(), $fbUser->token, $fbUser->refreshToken, $fbUser->expiresIn
    // -> Lưu token user, gọi /me/accounts để lấy Page tokens như bạn đang làm.

    // Ví dụ chuyển về dashboard:
    return redirect()->route('dashboard')->with('success', 'Đăng nhập Facebook thành công!');
})->name('facebook.callback');