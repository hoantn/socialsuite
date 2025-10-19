<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder; use Illuminate\Support\Facades\Hash;
use App\Models\{Page,Subscriber,Conversation,Message,BotFlow,BotStep,Campaign};
use App\Models\User;
class DemoSeeder extends Seeder {
  public function run(): void {
    $user = User::firstOrCreate(['email'=>'admin@mmo.homes'],['name'=>'Admin','password'=>Hash::make('password')]);
    $page = Page::create(['user_id'=>$user->id,'channel'=>'messenger','page_id'=>'DEMO_PAGE_123','name'=>'Demo Fanpage']);
    $sub = Subscriber::create(['page_id'=>$page->id,'psid'=>'PSID_A','name'=>'Nguyen A']);
    $conv = Conversation::create(['page_id'=>$page->id,'subscriber_id'=>$sub->id,'status'=>'bot']);
    Message::create(['conversation_id'=>$conv->id,'direction'=>'inbound','text'=>'Xin chào','sent_at'=>now()->subMinutes(5)]);
    Message::create(['conversation_id'=>$conv->id,'direction'=>'outbound','text'=>'Chào bạn!','sent_at'=>now()->subMinutes(4)]);
    $flow = BotFlow::create(['page_id'=>$page->id,'name'=>'Welcome Flow','is_active'=>true]);
    BotStep::create(['bot_flow_id'=>$flow->id,'type'=>'text','payload'=>['text'=>'Xin chào! Tôi có thể giúp gì?']]);
    Campaign::create(['page_id'=>$page->id,'name'=>'Khai trương','content'=>'Ưu đãi -20% tuần này','status'=>'draft']);
  }
}