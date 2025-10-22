<?php

namespace App\Http\Controllers;

use App\Models\FbPage;
use App\Models\ScheduledPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\CarbonImmutable;

class SchedulePostController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $pages = FbPage::query()
            ->where('user_id', $user->id ?? null)
            ->orderBy('name')
            ->get(['id','page_id','name','picture_url','category','access_token','connected_ig_id']);

        $items = ScheduledPost::query()
            ->orderByDesc('id')
            ->limit(15)
            ->get();

        $tz = $request->old('timezone', 'Asia/Ho_Chi_Minh');

        return view('schedule.index', compact('pages','items','tz'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'page_ids'   => ['required','array','min:1'],
            'message'    => ['nullable','string','max:2000'],
            'photos'     => ['required','array','min:1','max:5'],
            'photos.*'   => ['file','mimes:jpeg,png,jpg,gif','max:5120'],
            'timezone'   => ['required','string'],
            'publish_at' => ['required','date_format:Y-m-d H:i'],
        ]);

        $tz   = $request->input('timezone', 'Asia/Ho_Chi_Minh');
        $whenLocal = CarbonImmutable::createFromFormat('Y-m-d H:i', $request->input('publish_at'), $tz);
        $publishAtUtc = $whenLocal->setTimezone('UTC');

        $batchId = (string) Str::ulid();
        $mediaDir = "scheduled-media/{$batchId}";
        Storage::disk('local')->makeDirectory($mediaDir);

        $mediaPaths = [];
        foreach ($request->file('photos', []) as $i => $file) {
            if (!$file->isValid()) { continue; }
            $ext = strtolower($file->getClientOriginalExtension() ?: 'jpg');
            $name = sprintf('%02d.%s', $i+1, $ext);
            $path = "{$mediaDir}/{$name}";
            Storage::disk('local')->put($path, file_get_contents($file->getRealPath()));
            $mediaPaths[] = $path;
        }

        if (empty($mediaPaths)) {
            return back()->withErrors(['photos' => 'Không có ảnh nào được tải lên.']);
        }

        $created = [];
        foreach ($request->input('page_ids') as $pid) {
            $post = ScheduledPost::create([
                'page_id'       => $pid,
                'message'       => $request->string('message')->toString(),
                'media_paths'   => json_encode($mediaPaths, JSON_UNESCAPED_SLASHES),
                'media_count'   => count($mediaPaths),
                'media_type'    => count($mediaPaths) > 1 ? 'album' : 'photo',
                'timezone'      => $tz,
                'publish_at'    => $publishAtUtc->toDateTimeString(),
                'status'        => 'queued',
                'batch_id'      => $batchId,
            ]);
            $created[] = $post->id;
        }

        return redirect()->route('schedule.index')
            ->with('ok', 'Đã lưu ' . count($created) . ' lịch đăng.');
    }
}
