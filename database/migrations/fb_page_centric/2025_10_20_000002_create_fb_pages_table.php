<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('fb_pages', function (Blueprint $t) {
            $t->string('page_id')->primary();
            $t->string('name')->nullable();
            $t->string('username')->nullable();
            $t->string('category')->nullable();
            $t->string('avatar_url')->nullable();
            $t->string('connected_ig_id')->nullable();
            $t->text('page_access_token')->nullable();
            $t->timestamp('token_expires_at')->nullable();
            $t->json('capabilities')->nullable();
            $t->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('fb_pages');
    }
};
