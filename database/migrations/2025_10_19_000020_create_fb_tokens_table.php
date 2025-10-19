<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(){ Schema::create('fb_tokens', function(Blueprint $t){
        $t->id();
        $t->foreignId('page_id')->constrained()->cascadeOnDelete();
        $t->foreignId('user_id')->constrained()->cascadeOnDelete();
        $t->text('access_token')->nullable();
        $t->json('scopes')->nullable();
        $t->timestamp('expires_at')->nullable();
        $t->timestamps();
    });}
    public function down(){ Schema::dropIfExists('fb_tokens'); }
};
