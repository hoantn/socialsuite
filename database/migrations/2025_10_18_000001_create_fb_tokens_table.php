<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * [SOCIALSUITE][GPT][2025-10-18 08:45 +07]
 * CHANGE: Tạo bảng facebook_tokens
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facebook_tokens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('fb_user_id')->index();
            $table->string('fb_name')->nullable();
            $table->text('token');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facebook_tokens');
    }
};
