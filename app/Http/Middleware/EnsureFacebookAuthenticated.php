<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
class EnsureFacebookAuthenticated
{
  public function handle(Request $request, Closure $next){
    if (!$request->session()->get('fb_account_id')) return redirect('/auth/facebook/login');
    return $next($request);
  }
}