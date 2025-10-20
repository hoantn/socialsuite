<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AccountPage;

class EnsureHasPageAccess {
    public function handle(Request $request, Closure $next) {
        $pageId = $request->route('page_id');
        $accId = session('fb_account_id');
        if (!$pageId || !$accId) { abort(403, 'Thiếu thông tin xác thực.'); }
        $link = AccountPage::where('fb_account_id',$accId)->where('page_id',$pageId)->first();
        if (!$link) { abort(403, 'Bạn không có quyền trên Page này.'); }
        return $next($request);
    }
}
