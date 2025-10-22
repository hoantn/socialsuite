<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fb_users', function (Blueprint $table) {
            $table->id();
            $table->string('fb_user_id')->unique();
            $table->string('name')->nullable();
            $table->string('email')->nullable()->index();
            $table->string('picture_url')->nullable();
            $table->text('access_token')->nullable();
            $table->timestamp('access_token_expires_at')->nullable();
            $table->json('raw')->nullable();
            $table->timestamps();
        });

        Schema::create('fb_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('fb_users')->cascadeOnDelete();
            $table->string('page_id')->unique();
            $table->string('name')->nullable();
            $table->string('category')->nullable();
            $table->string('picture_url')->nullable();
            $table->string('connected_ig_id')->nullable();
            $table->json('raw')->nullable();
            $table->timestamps();
        });

        Schema::create('fb_page_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('page_id')->index();
            $table->text('access_token')->nullable();
            $table->timestamp('access_token_expires_at')->nullable();
            $table->json('raw')->nullable();
            $table->timestamps();
        });

        // jobs table cho queue (nếu chưa có, user có thể chạy `php artisan queue:table`)
        if (!Schema::hasTable('jobs')) {
            Schema::create('jobs', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('queue')->index();
                $table->longText('payload');
                $table->unsignedTinyInteger('attempts');
                $table->unsignedInteger('reserved_at')->nullable();
                $table->unsignedInteger('available_at');
                $table->unsignedInteger('created_at');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('fb_page_tokens');
        Schema::dropIfExists('fb_pages');
        Schema::dropIfExists('fb_users');
        // KHÔNG drop jobs table
    }
};
