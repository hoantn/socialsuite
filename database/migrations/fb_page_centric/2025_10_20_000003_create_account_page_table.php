<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('account_page', function (Blueprint $t) {
            $t->id();
            $t->foreignId('fb_account_id')->constrained('fb_accounts')->cascadeOnDelete();
            $t->string('page_id');
            $t->foreign('page_id')->references('page_id')->on('fb_pages')->cascadeOnDelete();
            $t->string('role')->nullable();
            $t->json('granted_scopes')->nullable();
            $t->timestamps();
            $t->unique(['fb_account_id','page_id']);
            $t->index('page_id');
        });
    }
    public function down(): void {
        Schema::dropIfExists('account_page');
    }
};
