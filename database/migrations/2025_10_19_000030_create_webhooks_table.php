<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(){ Schema::create('webhooks', function(Blueprint $t){
        $t->id();
        $t->foreignId('page_id')->constrained()->cascadeOnDelete();
        $t->string('topic')->default('messages');
        $t->boolean('subscribed')->default(false);
        $t->string('verify_token')->nullable();
        $t->timestamps();
        $t->unique(['page_id','topic']);
    });}
    public function down(){ Schema::dropIfExists('webhooks'); }
};
