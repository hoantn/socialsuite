<?php

namespace App\Http\Controllers;

use App\Models\ScheduledPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ScheduledPostController extends Controller
{
    /**
     * Trang tạo lịch đăng.
     * - Truyền $pages cho view (select Page)
     * - Truyền $scheduled để hiển thị danh sách lịch gần nhất
     */
    public function index(Request $request)
    {
        $tz = $request->user() && method_exists($request->user(), 'timezone')
            ? ($request->user()->timezone ?? 'UTC')
            : 'UTC';

        // Lấy danh sách Page. Nếu cần ràng buộc theo owner, có thể thêm where('owner_id', auth()->id()).
        $pages = DB::table('fb_pages')
            ->orderBy('name')
            ->get();

        $scheduled = ScheduledPost::orderByDesc('id')->limit(15)->get();

        return view('schedule.index', [
            'pages'           => $pages,
            'scheduled'       => $scheduled,
            'defaultTimezone' => $tz,
        ]);
    }

    /**
     * Lưu lịch đăng.
     * - FIX lỗi append(): dùng $mediaPaths[] = $path
     * - 1 ảnh  => media_path + media_type = 'photo'
     * - >=2 ảnh => media_paths (json) + media_type = 'album'
     * - publish_at: lưu UTC dựa trên timezone người dùng chọn
     */
    public function store(Request $request)
    {
        $request->validate([
            'page_id'    => ['required', 'string'],
            'page_name'  => ['nullable', 'string'],
            'message'    => ['nullable', 'string'],
            'timezone'   => ['required', 'string'],  // ví dụ: Asia/Ho_Chi_Minh
            'publish_at' => ['required', 'date'],    // nếu submit ISO 8601 từ input datetime-local là OK
            'photos'     => ['nullable', 'array', 'max:5'],
            'photos.*'   => ['nullable', 'file', 'image', 'max:5120'], // 5MB
        ]);

        $mediaPaths = [];

        // Lưu từng ảnh (bỏ qua phần tử rỗng)
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                if ($file && $file->isValid()) {
                    // Lưu vào storage/app/scheduled_media
                    $path = $file->store('scheduled_media');
                    $mediaPaths[] = $path; // << đúng cú pháp PHP
                }
            }
        }

        // Convert thời gian người dùng chọn -> UTC
        // Nếu submit ISO 8601: Carbon::parse(..., timezone)->utc() xử lý được
        $publishUtc = Carbon::parse(
            $request->input('publish_at'),
            $request->input('timezone')
        )->utc();

        // Nếu bạn submit dưới dạng custom "d/m/Y H:i", thay bằng:
        // $publishUtc = Carbon::createFromFormat('d/m/Y H:i', $request->input('publish_at'), $request->input('timezone'))->utc();

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

            // Ảnh đơn
            'media_path'  => count($mediaPaths) === 1 ? $mediaPaths[0] : null,
            // Album
            'media_paths' => count($mediaPaths) > 1  ? $mediaPaths : null,
            'media_type'  => $mediaType,

            'timezone'    => $request->input('timezone'),
            'publish_at'  => $publishUtc, // lưu UTC

            'status'      => 'queued', // queued|processing|published|failed|canceled
        ]);

        return redirect()->back()->with('success', 'Đã lưu lịch #' . $post->id);
    }

    /**
     * Hủy một lịch (nếu còn trong queued/processing).
     */
    public function cancel(ScheduledPost $post)
    {
        if (in_array($post->status, ['queued', 'processing'])) {
            $post->status = 'canceled';
            $post->save();
        }

        return redirect()->back()->with('success', 'Đã hủy lịch #' . $post->id);
    }
}
