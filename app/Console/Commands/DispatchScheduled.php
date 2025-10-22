<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\ScheduledPost;
use App\Jobs\PublishScheduledPost;
use Carbon\Carbon;

class DispatchScheduled extends Command {
  protected $signature = 'socialsuite:dispatch-scheduled';
  protected $description = 'Dispatch ready scheduled posts to queue';
  public function handle(): int {
    $nowUtc = Carbon::now('UTC');
    $ready = ScheduledPost::where('status','queued')->where('publish_at','<=',$nowUtc)->orderBy('publish_at')->limit(100)->get();
    $count=0;
    foreach($ready as $sp){ dispatch(new PublishScheduledPost($sp->id)); $sp->status='processing'; $sp->save(); $count++; }
    $this->info("Dispatched: {$count}");
    return Command::SUCCESS;
  }
}
