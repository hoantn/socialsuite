<?php
namespace App\Jobs;
use App\Models\ScheduledPost;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class PublishScheduledPost implements ShouldQueue {
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
  public int $timeout = 120;
  public int $tries = 3;
  public function __construct(public int $scheduledPostId){}
  public function handle(): void {
    $sp = ScheduledPost::find($this->scheduledPostId);
    if(!$sp) return;
    try {
      // TODO: integrate your actual Facebook publisher here.
      $sp->status='done'; $sp->save();
    } catch (Throwable $e){
      $sp->status='failed'; $sp->tries = ($sp->tries ?? 0)+1; $sp->last_error=$e->getMessage(); $sp->save();
      throw $e;
    }
  }
}
