<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\FbPage;

class HealthController extends Controller {
    public function index() {
        $pages = FbPage::orderBy('name')->get(['page_id','name','token_expires_at']);
        $queueSize = DB::table('jobs')->count();
        $failed = DB::table('failed_jobs')->count();
        return view('health.index', compact('pages','queueSize','failed'));
    }
}
