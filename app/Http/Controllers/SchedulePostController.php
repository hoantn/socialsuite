<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SchedulePostController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        // Lấy danh sách Page đã kết nối
        $pages = DB::table('fb_pages')
            ->when($userId, fn($q)=>$q->where('user_id', $userId))
            ->orderBy('name')
            ->select('id','page_id','name','access_token','picture_url','connected_ig_id','category')
            ->get();

        // Lấy 15 lịch gần nhất
        $items = DB::table('scheduled_posts')
            ->orderByDesc('id')
            ->limit(15)
            ->get();

        $tz = $request->get('tz', config('app.timezone', 'Asia/Ho_Chi_Minh'));

        return view('schedule.index', [
            'title' => 'Lên lịch đăng',
            'pages' => $pages,
            'items' => $items,
            'tz'    => $tz,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'page_ids'    => 'required|array|min:1',
            'message'     => 'nullable|string|max:2000',
            'publish_at'  => 'required|date',
            'timezone'    => 'required|string',
            'photos.*'    => 'nullable|file|mimetypes:image/jpeg,image/png,image/webp|max:5120',
            'videos.*'    => 'nullable|file|mimetypes:video/mp4,video/quicktime|max:51200',
        ],[
            'page_ids.required' => 'Chọn ít nhất 1 Page.',
            'publish_at.required'=> 'Vui lòng chọn thời điểm đăng.',
        ]);

        // Gom media
        $media = [];
        $batchId = now()->format('Ymd-His-').Str::random(6);

        // Ảnh
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $i => $file) {
                if (!$file->isValid()) continue;
                $name = str_pad($i+1, 2, '0', STR_PAD_LEFT) . '.' . $file->getClientOriginalExtension();
                $path = "scheduled-media/{$batchId}/".$name;
                Storage::disk('local')->put($path, file_get_contents($file->getRealPath()));
                $media[] = ['type'=>'image','path'=>$path];
            }
        }
        // Video (cho phép 1 video duy nhất)
        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $i => $file) {
                if (!$file->isValid()) continue;
                $name = 'video'.($i+1).'.'.$file->getClientOriginalExtension();
                $path = "scheduled-media/{$batchId}/".$name;
                Storage::disk('local')->put($path, file_get_contents($file->getRealPath()));
                $media[] = ['type'=>'video','path'=>$path];
            }
        }

        if (empty($media)) {
            return back()->with('error','Bạn chưa đính kèm ảnh hoặc video nào.');
        }
        // Facebook không hỗn hợp ảnh & video trong 1 bài: chấp nhận hoặc từ chối
        $types = collect($media)->pluck('type')->unique()->values();
        if ($types->count()>1) {
            return back()->with('error','Facebook không cho phép đăng chung ảnh và video trong một bài. Vui lòng chọn chỉ ảnh (1-5) hoặc chỉ 1 video.');
        }

        $tz = $request->string('timezone') ?: config('app.timezone','Asia/Ho_Chi_Minh');
        $publishLocal = Carbon::parse($request->input('publish_at'), $tz);
        $publishUtc   = $publishLocal->clone()->setTimezone('UTC');

        foreach ($request->input('page_ids') as $pageId) {
            DB::table('scheduled_posts')->insert([
                'page_id'      => $pageId,
                'message'      => $request->input('message'),
                'media_paths'  => json_encode($media, JSON_UNESCAPED_SLASHES),
                'media_count'  => count($media),
                'media_type'   => $types->first(), // image|video
                'timezone'     => $tz,
                'publish_at'   => $publishUtc->toDateTimeString(),
                'status'       => 'queued',
                'batch_id'     => $batchId,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }

        return redirect()->route('schedule.index')->with('ok','Đã lưu lịch đăng.');
    }

    public function cancel($id)
    {
        DB::table('scheduled_posts')->where('id',$id)->update([
            'status'=>'cancelled',
            'updated_at'=>now(),
        ]);
        return back()->with('ok','Đã hủy lịch.');
    }
}
