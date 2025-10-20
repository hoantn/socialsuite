<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{FbPage, PageConfig};

class PageConfigController extends Controller {
    public function edit($page_id) {
        $page = FbPage::findOrFail($page_id);
        $cfg = PageConfig::firstOrCreate(['page_id'=>$page_id], ['settings'=>json_encode(new \stdClass())]);
        return view('pages.settings', compact('page','cfg'));
    }

    public function update(Request $r, $page_id) {
        $cfg = PageConfig::firstOrCreate(['page_id'=>$page_id]);
        $cfg->settings = [
            'posting_defaults' => $r->input('posting_defaults', []),
            'schedule' => $r->input('schedule', []),
            'auto_reply' => $r->input('auto_reply', ['enabled'=>false]),
        ];
        $cfg->updated_by = session('fb_account_id');
        $cfg->save();
        return back()->with('ok', 'Đã lưu cấu hình Page.');
    }
}
