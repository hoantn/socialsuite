<?php
namespace App\Jobs;
use App\Services\MetaGraph;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendFacebookMessage implements ShouldQueue {
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  public function __construct(public string $pageToken, public string $psid, public string $text) {}

  public function handle(MetaGraph $graph): void {
    $graph->sendMessage($this->pageToken, $this->psid, ['text' => $this->text]);
  }
}