<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('webhook_subscriptions', function (Blueprint $t) {
            $t->id();
            $t->string('page_id');
            $t->foreign('page_id')->references('page_id')->on('fb_pages')->cascadeOnDelete();
            $t->string('verify_token');
            $t->string('callback_url');
            $t->json('fields')->nullable();
            $t->timestamps();
            $t->unique('page_id');
        });
    }
    public function down(): void {
        Schema::dropIfExists('webhook_subscriptions');
    }
};
