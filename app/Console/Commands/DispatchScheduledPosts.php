<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Jobs\PublishScheduledPost;

class DispatchScheduledPosts extends Command
{
    protected $signature = 'socialsuite:dispatch-scheduled';
    protected $description = 'Dispatch jobs to publish due scheduled posts';

    public function handle()
    {
        $now = Carbon::now('UTC')->toDateTimeString();

        $rows = DB::table('scheduled_posts')
            ->where('status','queued')
            ->where('publish_at','<=',$now)
            ->limit(100)
            ->get();

        foreach ($rows as $r) {
            PublishScheduledPost::dispatch($r->id);
        }

        $this->info('Dispatched: '.$rows->count());
        return 0;
    }
}
