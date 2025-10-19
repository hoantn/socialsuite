<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(){ Schema::create('conversations', function(Blueprint $t){
        $t->id();
        $t->foreignId('page_id')->constrained()->cascadeOnDelete();
        $t->string('psid')->index();
        $t->timestamp('last_message_at')->nullable();
        $t->timestamps();
        $t->unique(['page_id','psid']);
    });}
    public function down(){ Schema::dropIfExists('conversations'); }
};
