<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use App\Models\FbUser;
use App\Models\FbPage;
use App\Models\ScheduledPost;

class ScheduledPostController extends Controller
{
    public function index(Request $request)
    {
        $uid = session('fb_uid');
        $user = $uid ? FbUser::find($uid) : null;
        $pages = $user ? FbPage::where('owner_id',$user->id)->orderBy('name')->get() : collect();

        $list = ScheduledPost::orderByDesc('publish_at')->paginate(15);
        return view('schedule.index', compact('pages','list'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'page_id' => 'required|string',
            'message' => 'nullable|string|max:63206',
            'photo'   => 'nullable|file|mimes:jpg,jpeg,png,gif|max:5120',
            'publish_at' => 'required|date_format:Y-m-d\TH:i',
            'timezone'   => 'required|string',
        ]);

        $pageId = $request->input('page_id');
        $page = \App\Models\FbPage::where('page_id',$pageId)->first();

        $tz = new \DateTimeZone($request->input('timezone'));
        $dtLocal = \DateTime::createFromFormat('Y-m-d\TH:i', $request->input('publish_at'), $tz);
        $dtUtc = (clone $dtLocal)->setTimezone(new \DateTimeZone('UTC'));

        $mediaPath = null;
        if ($request->hasFile('photo')) {
            $mediaPath = $request->file('photo')->store('scheduled_media');
        }

        ScheduledPost::create([
            'page_id' => $pageId,
            'page_name' => $page->name ?? null,
            'message' => $request->input('message'),
            'media_path' => $mediaPath,
            'timezone' => $request->input('timezone'),
            'publish_at' => $dtUtc->format('Y-m-d H:i:s'),
            'status' => 'queued',
        ]);

        return back()->with('status','Đã tạo lịch đăng.');
    }

    public function cancel(Request $request, int $id)
    {
        $sch = ScheduledPost::findOrFail($id);
        if (!in_array($sch->status, ['queued','processing'])) {
            return back()->with('status','Không thể hủy lịch ở trạng thái: '.$sch->status);
        }
        $sch->status = 'canceled';
        $sch->save();
        return back()->with('status','Đã hủy lịch.');
    }
}
