<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(){ Schema::create('pages', function(Blueprint $t){
        $t->id();
        $t->string('platform')->default('facebook');
        $t->string('page_id')->index();
        $t->string('name')->nullable();
        $t->string('avatar_url')->nullable();
        $t->string('category')->nullable();
        $t->boolean('connected')->default(false);
        $t->timestamps();
        $t->unique(['platform','page_id']);
    });}
    public function down(){ Schema::dropIfExists('pages'); }
};
