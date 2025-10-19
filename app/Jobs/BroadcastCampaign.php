<?php
namespace App\Jobs;
use App\Models\{Campaign,Subscriber,Page};
use App\Services\MetaGraph;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BroadcastCampaign implements ShouldQueue {
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  public function __construct(public int $campaignId) {}

  public function handle(MetaGraph $graph): void {
    $camp = Campaign::find($this->campaignId);
    if (!$camp) return;
    $page = Page::find($camp->page_id);
    if (!$page || !$page->access_token) return;
    Subscriber::where('page_id', $page->id)->chunk(100, function($subs) use($graph, $page, $camp){
      foreach($subs as $s) { $graph->sendMessage($page->access_token, $s->psid, ['text' => $camp->content]); }
    });
    $camp->update(['status' => 'sent']);
  }
}