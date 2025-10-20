<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Auto-register facebook API routes to avoid editing routes files
        Route::middleware('api')
            ->prefix('api/facebook')
            ->group(function () {
                Route::get('/pages', [\App\Http\Controllers\Api\FacebookController::class, 'pages']);
                Route::post('/import', [\App\Http\Controllers\Api\FacebookController::class, 'importPages']);
                Route::post('/subscribe', [\App\Http\Controllers\Api\FacebookController::class, 'subscribe']);
                Route::get('/callback', [\App\Http\Controllers\Api\FacebookController::class, 'oauthCallback']);
            });

        Route::match(['get','post'],'/webhook/facebook', [\App\Http\Controllers\Api\FacebookController::class, 'webhook']);
    }
}
