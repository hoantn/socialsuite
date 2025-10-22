<?php

namespace App\Http\Controllers;

use App\Models\ScheduledPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use DateTimeZone;

class ScheduledPostController extends Controller
{
    /**
     * Trang compose & lên lịch đăng nhiều ảnh
     */
    public function index(Request $request)
    {
        // Lấy timezone người dùng nếu hệ thống bạn có cột timezone cho user, fallback UTC
        $tz = $request->user() && method_exists($request->user(), 'timezone')
            ? ($request->user()->timezone ?? 'UTC')
            : 'UTC';

        // Danh sách Page (tuỳ hệ thống bạn có owner_id thì thêm where owner)
        $pages = DB::table('fb_pages')->orderBy('name')->get();

        // Danh sách lịch mới nhất
        $scheduled = ScheduledPost::orderByDesc('id')->limit(15)->get();

        // Danh sách timezone để hiển thị
        $timezones = DateTimeZone::listIdentifiers();

        return view('schedule.index', [
            'pages'     => $pages,
            'scheduled' => $scheduled,
            'tz'        => $tz,
            'timezones' => $timezones,
        ]);
    }

    /**
     * Lưu lịch đăng nhiều ảnh (2–5 ảnh) hoặc 1 ảnh/không ảnh
     * - FIX append(): dùng $mediaPaths[] = $path
     * - 1 ảnh  -> media_path + media_type = 'photo'
     * - >=2 ảnh-> media_paths (json) + media_type = 'album'
     * - publish_at lưu UTC dựa trên timezone người dùng chọn
     */
    public function store(Request $request)
    {
        $request->validate([
            'page_id'    => ['required', 'string'],
            'page_name'  => ['nullable', 'string'],
            'message'    => ['nullable', 'string'],
            'timezone'   => ['required', 'string'],  // ví dụ: Asia/Ho_Chi_Minh
            'publish_at' => ['required', 'date'],    // submit ISO 8601 là OK
            'photos'     => ['nullable', 'array', 'max:5'],
            'photos.*'   => ['nullable', 'file', 'image', 'max:5120'], // 5MB
        ]);

        $mediaPaths = [];

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store('scheduled_media'); // storage/app/scheduled_media
                    $mediaPaths[] = $path; // <- đúng cú pháp PHP
                }
            }
        }

        // Convert giờ user chọn -> UTC
        $publishUtc = Carbon::parse(
            $request->input('publish_at'),
            $request->input('timezone')
        )->utc();

        // Xác định loại media
        $mediaType = null;
        if (count($mediaPaths) > 1) {
            $mediaType = 'album';
        } elseif (count($mediaPaths) === 1) {
            $mediaType = 'photo';
        }

        $post = ScheduledPost::create([
            'page_id'     => $request->input('page_id'),
            'page_name'   => $request->input('page_name'),
            'message'     => $request->input('message'),

            'media_path'  => count($mediaPaths) === 1 ? $mediaPaths[0] : null,
            'media_paths' => count($mediaPaths) > 1  ? $mediaPaths : null,
            'media_type'  => $mediaType,

            'timezone'    => $request->input('timezone'),
            'publish_at'  => $publishUtc,      // LƯU UTC
            'status'      => 'queued',         // queued|processing|published|failed|canceled
        ]);

        return redirect()->route('schedule.index')->with('success', 'Đã lưu lịch #' . $post->id);
    }

    /**
     * Hủy lịch (nếu còn queued/processing)
     */
    public function cancel(ScheduledPost $post)
    {
        if (in_array($post->status, ['queued', 'processing'])) {
            $post->status = 'canceled';
            $post->save();
        }
        return redirect()->route('schedule.index')->with('success', 'Đã hủy lịch #' . $post->id);
    }
}
