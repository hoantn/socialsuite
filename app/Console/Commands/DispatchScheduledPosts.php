<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ScheduledPost;
use App\Jobs\PublishScheduledPost;
use Carbon\CarbonImmutable;

class DispatchScheduledPosts extends Command
{
    protected $signature = 'socialsuite:dispatch-scheduled';
    protected $description = 'Dispatch scheduled posts whose publish_at (UTC) is due.';

    public function handle(): int
    {
        $now = CarbonImmutable::now('UTC')->startSecond();
        $due = ScheduledPost::query()
            ->where('status', 'queued')
            ->where('publish_at', '<=', $now->toDateTimeString())
            ->orderBy('id')
            ->limit(100)
            ->get(['id']);

        foreach ($due as $row) {
            PublishScheduledPost::dispatch($row->id);
        }

        $this->info("Dispatched: " . $due->count());
        return self::SUCCESS;
    }
}
