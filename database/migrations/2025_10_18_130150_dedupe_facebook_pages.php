<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * [SOCIALSUITE][HOTFIX] Dedupe facebook_pages by page_id before adding UNIQUE.
 * - Giữ bản ghi có id nhỏ nhất cho mỗi page_id
 * - Chuyển mọi liên kết (posts, page_memberships) về bản ghi giữ lại
 * - Xóa các bản ghi facebook_pages dư
 */
return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('facebook_pages')) return;

        // Tìm các page_id bị trùng (khác NULL)
        $dups = DB::table('facebook_pages')
            ->select('page_id', DB::raw('COUNT(*) as c'))
            ->whereNotNull('page_id')
            ->groupBy('page_id')
            ->having('c', '>', 1)
            ->pluck('page_id');

        foreach ($dups as $pid) {
            // Lấy danh sách các dòng cho page_id này, giữ id nhỏ nhất
            $rows = DB::table('facebook_pages')->where('page_id', $pid)->orderBy('id')->get();
            if ($rows->count() < 2) continue;

            $keepId = $rows->first()->id;
            $dropIds = $rows->pluck('id')->filter(fn($id) => $id !== $keepId)->values();

            if ($dropIds->isEmpty()) continue;

            // Cập nhật các FK trỏ sang bản ghi bị drop -> chuyển về keepId
            if (Schema::hasTable('posts') && Schema::hasColumn('posts', 'facebook_page_id')) {
                DB::table('posts')->whereIn('facebook_page_id', $dropIds)->update(['facebook_page_id' => $keepId]);
            }
            if (Schema::hasTable('page_memberships')) {
                DB::table('page_memberships')->whereIn('facebook_page_id', $dropIds)->update(['facebook_page_id' => $keepId]);
            }

            // Xóa các bản ghi facebook_pages dư
            DB::table('facebook_pages')->whereIn('id', $dropIds)->delete();
        }
    }

    public function down(): void
    {
        // Không rollback dữ liệu đã dọn
    }
};
