# SocialSuite — DEV-stable OAuth patch (refresh)

This bundle replaces 4 files:

- app/Services/FacebookClient.php
- app/Support/Facebook/LaravelPersistentDataHandler.php
- app/Http/Controllers/AuthController.php
- config/facebook.php

After copying:
    php artisan optimize:clear
    php artisan config:clear

Routes (web.php):
Route::middleware('web')->group(function () {
    Route::get('auth/facebook/redirect', [\App\Http\Controllers\AuthController::class, 'redirect'])->name('fb.redirect');
    Route::get('auth/facebook/callback', [\App\Http\Controllers\AuthController::class, 'callback'])->name('fb.callback');
});
