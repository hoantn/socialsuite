<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration {
  public function up(): void {
    Schema::create('pages', function(Blueprint $t){ $t->id(); $t->unsignedBigInteger('user_id')->index(); $t->string('channel')->default('messenger'); $t->string('page_id')->index(); $t->string('name'); $t->string('avatar')->nullable(); $t->text('access_token')->nullable(); $t->timestamps(); });
    Schema::create('subscribers', function(Blueprint $t){ $t->id(); $t->foreignId('page_id')->constrained()->cascadeOnDelete(); $t->string('psid')->index(); $t->string('name')->nullable(); $t->string('avatar')->nullable(); $t->boolean('opted_out')->default(false); $t->timestamps(); });
    Schema::create('conversations', function(Blueprint $t){ $t->id(); $t->foreignId('page_id')->constrained()->cascadeOnDelete(); $t->foreignId('subscriber_id')->constrained()->cascadeOnDelete(); $t->string('status')->default('bot'); $t->timestamps(); });
    Schema::create('messages', function(Blueprint $t){ $t->id(); $t->foreignId('conversation_id')->constrained()->cascadeOnDelete(); $t->string('direction'); $t->text('text')->nullable(); $t->timestamp('sent_at')->nullable(); });
    Schema::create('bot_flows', function(Blueprint $t){ $t->id(); $t->foreignId('page_id')->constrained('pages')->cascadeOnDelete(); $t->string('name'); $t->boolean('is_active')->default(true); $t->timestamps(); });
    Schema::create('bot_steps', function(Blueprint $t){ $t->id(); $t->foreignId('bot_flow_id')->constrained('bot_flows')->cascadeOnDelete(); $t->string('type'); $t->json('payload')->nullable(); $t->unsignedBigInteger('next_step_id')->nullable(); $t->string('keyword')->nullable(); $t->timestamps(); });
    Schema::create('campaigns', function(Blueprint $t){ $t->id(); $t->foreignId('page_id')->constrained()->cascadeOnDelete(); $t->string('name'); $t->text('content'); $t->string('status')->default('draft'); $t->timestamp('scheduled_at')->nullable(); $t->timestamps(); });
  }
  public function down(): void {
    Schema::dropIfExists('campaigns'); Schema::dropIfExists('bot_steps'); Schema::dropIfExists('bot_flows'); Schema::dropIfExists('messages'); Schema::dropIfExists('conversations'); Schema::dropIfExists('subscribers'); Schema::dropIfExists('pages');
  }
};