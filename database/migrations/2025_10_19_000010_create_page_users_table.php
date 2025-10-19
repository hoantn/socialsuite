<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(){ Schema::create('page_users', function(Blueprint $t){
        $t->id();
        $t->foreignId('page_id')->constrained()->cascadeOnDelete();
        $t->foreignId('user_id')->constrained()->cascadeOnDelete();
        $t->timestamps();
        $t->unique(['page_id','user_id']);
    });}
    public function down(){ Schema::dropIfExists('page_users'); }
};
