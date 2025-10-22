<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use App\Models\ScheduledPost;
use App\Jobs\PublishScheduledPostJob;

class DispatchScheduledPosts extends Command
{
    protected $signature = 'socialsuite:dispatch-scheduled';
    protected $description = 'Dispatch due scheduled posts to the queue';

    public function handle(): int
    {
        $now = now(); // UTC
        $due = ScheduledPost::where('status','queued')
            ->where('publish_at','<=',$now)
            ->limit(50)->get();

        foreach ($due as $sch) {
            PublishScheduledPostJob::dispatch($sch->id);
            $sch->status = 'processing'; // mark early to avoid double dispatch
            $sch->save();
        }

        $this->info('Dispatched: '.$due->count());
        return self::SUCCESS;
    }
}
