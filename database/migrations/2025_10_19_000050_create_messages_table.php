<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(){ Schema::create('messages', function(Blueprint $t){
        $t->id();
        $t->foreignId('conversation_id')->constrained()->cascadeOnDelete();
        $t->string('direction',8);
        $t->string('type',16)->default('text');
        $t->json('payload')->nullable();
        $t->timestamp('sent_at')->nullable();
        $t->timestamps();
        $t->index(['conversation_id','sent_at']);
    });}
    public function down(){ Schema::dropIfExists('messages'); }
};
