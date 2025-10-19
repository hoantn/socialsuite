<?php

use Illuminate\Support\Facades\Route;

Route::get('/healthz', fn() => response()->json(['ok' => true]));

// Catch-all to serve the SPA index; keeps PHP routes minimal.
// If you host app on subfolder, adjust regex accordingly.
Route::get('/{any}', function () {
    return view('app'); // resources/views/app.blade.php
})->where('any', '.*');
