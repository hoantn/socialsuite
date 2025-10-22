<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class FacebookController extends BaseController
{
    // Phase 2: Sẽ redirect tới Facebook OAuth
    public function redirect(Request $request)
    {
        return redirect()->route('home')->with('status', 'OAuth chưa bật ở Phase 1. Sẽ có ở Phase 2.');
    }

    // Phase 2: Handle callback & lưu token
    public function callback(Request $request)
    {
        return redirect()->route('home')->with('status', 'Callback stub (Phase 2 sẽ xử lý).');
    }

    public function dashboard(Request $request)
    {
        return view('dashboard', [
            'user' => null, // Phase 2 sẽ truyền user FB
            'pages' => [],  // Phase 2 sẽ load list pages
        ]);
    }
}
