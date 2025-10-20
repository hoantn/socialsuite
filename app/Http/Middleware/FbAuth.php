<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class FbAuth {
    public function handle(Request $request, Closure $next) {
        if (!session()->has('fb_account_id')) {
            return redirect()->route('fb.redirect');
        }
        return $next($request);
    }
}
