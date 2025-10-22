<?php

namespace App\Http\Controllers;

use App\Models\ScheduledPost;
use App\Models\FbPage; // đổi theo model page của bạn, nếu khác thì require lại tên đúng
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SchedulePostController extends Controller
{
    public function index(Request $request)
    {
        // Lấy danh sách pages của user (đổi model/fields cho đúng repo của bạn)
        $pages = FbPage::query()
            ->where('user_id', Auth::id())
            ->orderBy('name')
            ->get(['id', 'page_id', 'name', 'picture_url']);

        // Lấy 15 lịch gần nhất
        $items = ScheduledPost::query()
            ->latest('id')
            ->limit(15)
            ->get()
            ->map(function ($it) {
                $tz = data_get($it->meta, 'tz', 'Asia/Ho_Chi_Minh');
                $it->tz = $tz;
                if ($it->publish_at) {
                    $it->publish_local = $it->publish_at->copy()->setTimezone($tz);
                }
                $count = is_array($it->media_paths) ? count($it->media_paths) : 0;
                $it->media_type = $count > 1 ? 'album' : ($count === 1 ? 'photo' : null);
                return $it;
            });

        return view('schedule.index', compact('pages', 'items'));
    }

    public function store(Request $request)
    {
        // Form gửi: page_ids[], message, photos[] (multiple), tz, publish_at_local (Y-m-d H:i)
        $data = $request->validate([
            'page_ids'           => ['required', 'array', 'min:1'],
            'page_ids.*'         => ['string'],
            'message'            => ['nullable', 'string', 'max:10000'],
            'photos'             => ['nullable'],
            'photos.*'           => ['file', 'image', 'max:5120'], // 5MB/ảnh
            'tz'                 => ['required', 'string'],
            'publish_at_local'   => ['required', 'date_format:Y-m-d H:i'],
        ]);

        // Chuyển thời gian người dùng chọn -> UTC để lưu DB
        $tz = $data['tz'];
        $publishLocal = Carbon::createFromFormat('Y-m-d H:i', $data['publish_at_local'], $tz);
        $publishUtc   = $publishLocal->clone()->setTimezone('UTC');

        // Lưu ảnh vào storage/public/schedule_media
        $mediaPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                if (!$file) continue;
                if (!$file->isValid()) continue;

                // NOTE: cần chạy `php artisan storage:link` 1 lần để public link hoạt động
                $path = $file->store('schedule_media', 'public');
                $mediaPaths[] = 'storage/'.$path; // đường dẫn public
            }
        }

        // Tạo lịch cho từng page
        foreach ($data['page_ids'] as $pid) {
            ScheduledPost::create([
                'user_id'     => Auth::id(),
                'page_id'     => $pid,
                'message'     => $data['message'] ?? null,
                'media_paths' => $mediaPaths,             // cast json
                'publish_at'  => $publishUtc,             // UTC
                'status'      => 'queued',
                'meta'        => [
                    'tz'            => $tz,
                    'publish_local' => $publishLocal->toDateTimeString(),
                    'media_count'   => count($mediaPaths),
                ],
            ]);
        }

        return redirect()->route('schedule.index')->with('ok', 'Đã lưu lịch thành công.');
    }

    public function cancel(Request $request, ScheduledPost $scheduled)
    {
        if ($scheduled->status === 'queued') {
            $scheduled->status = 'canceled';
            $scheduled->save();
        }
        return back();
    }
}
