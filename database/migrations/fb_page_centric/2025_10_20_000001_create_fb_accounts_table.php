<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('fb_accounts', function (Blueprint $t) {
            $t->id();
            $t->string('fb_user_id')->unique();
            $t->string('name')->nullable();
            $t->string('avatar_url')->nullable();
            $t->text('user_access_token')->nullable(); // long-lived
            $t->timestamp('token_expires_at')->nullable();
            $t->json('granted_scopes')->nullable();
            $t->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('fb_accounts');
    }
};
