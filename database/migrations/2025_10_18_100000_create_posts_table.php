<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/**
 * [SOCIALSUITE][GPT][2025-10-18 10:44 +07] Create posts table for Page publishing & scheduling
 */
return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('facebook_pages')) {
            // fail-safe: basic pages table (if user chưa có)
            Schema::create('facebook_pages', function (Blueprint $table) {
                $table->id();
                $table->string('fb_user_id');
                $table->string('page_id')->index();
                $table->string('name');
                $table->text('access_token')->nullable();
                $table->timestamps();
            });
        }
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('facebook_page_id')->index();
            $table->string('type')->default('text'); // text|photo|link
            $table->text('message')->nullable();
            $table->string('link')->nullable();
            $table->string('image_url')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->string('status')->default('draft'); // draft|scheduled|publishing|published|failed
            $table->string('fb_post_id')->nullable();
            $table->text('error')->nullable();
            $table->timestamps();
            $table->foreign('facebook_page_id')->references('id')->on('facebook_pages')->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
