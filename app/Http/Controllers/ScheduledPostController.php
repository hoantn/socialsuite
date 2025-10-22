<?php

namespace App\Http\Controllers;

use App\Models\ScheduledPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ScheduledPostController extends Controller
{
    /**
     * Hiển thị trang lên lịch (nếu dự án bạn đã có index riêng, có thể bỏ qua thay đổi này)
     */
    public function index(Request $request)
    {
        $tz = $request->user() && method_exists($request->user(), 'timezone')
            ? ($request->user()->timezone ?? 'UTC')
            : 'UTC';

        $scheduled = ScheduledPost::orderByDesc('id')->limit(15)->get();

        return view('schedule.index', [
            'scheduled' => $scheduled,
            'defaultTimezone' => $tz,
        ]);
    }

    /**
     * Lưu lịch đăng — FIX:
     * - Sửa lỗi gọi append() -> dùng $mediaPaths[] = ...
     * - Hỗ trợ nhiều ảnh:
     *     + 1 ảnh  : media_path + media_type = 'photo'
     *     + >=2 ảnh: media_paths (json) + media_type = 'album'
     * - publish_at luôn lưu theo UTC từ timezone người dùng chọn
     */
    public function store(Request $request)
    {
        $request->validate([
            'page_id'    => ['required', 'string'],
            'page_name'  => ['nullable', 'string'],
            'message'    => ['nullable', 'string'],
            'timezone'   => ['required', 'string'], // VD: Asia/Ho_Chi_Minh
            'publish_at' => ['required', 'date'],   // bạn đang submit ISO 8601 => OK
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
        // Nếu submit ISO 8601 (chuẩn), Carbon parse trực tiếp:
        $publishUtc = Carbon::parse(
            $request->input('publish_at'),
            $request->input('timezone')
        )->utc();

        // Nếu bạn submit "dd/mm/yyyy HH:ii" (không phải ISO), dùng:
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

            // 1 ảnh -> media_path
            'media_path'  => count($mediaPaths) === 1 ? $mediaPaths[0] : null,
            // >=2 ảnh -> media_paths (json)
            'media_paths' => count($mediaPaths) > 1  ? $mediaPaths : null,
            'media_type'  => $mediaType,

            'timezone'    => $request->input('timezone'),
            'publish_at'  => $publishUtc, // lưu UTC

            'status'      => 'queued', // queued|processing|published|failed|canceled
        ]);

        return redirect()->back()->with('success', 'Đã lưu lịch #' . $post->id);
    }

    /**
     * Hủy lịch (tuỳ luồng)
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
