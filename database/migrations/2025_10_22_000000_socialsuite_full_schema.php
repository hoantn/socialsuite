<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * SocialSuite — Full Schema (gộp từ tất cả migration bạn đã gửi)
     *
     * Bảng tạo:
     * - fb_users
     * - fb_pages
     * - fb_page_tokens
     * - fb_posts (đã gộp thêm các cột chi tiết: type/status/error/response)
     * - scheduled_posts (đã gộp media_paths + media_type)
     * - cache (chuẩn Laravel)
     * - jobs (chuẩn Laravel)
     */

    public function up(): void
    {
        /**
         * FB USERS
         */
        if (!Schema::hasTable('fb_users')) {
            Schema::create('fb_users', function (Blueprint $table) {
                $table->id();
                $table->string('fb_user_id')->unique();       // ID người dùng Facebook
                $table->string('name')->nullable();
                $table->string('email')->nullable();
                $table->string('picture_url')->nullable();
                $table->text('access_token')->nullable();
                $table->timestamp('access_token_expires_at')->nullable();
                $table->json('raw')->nullable();               // dữ liệu FB trả về
                $table->timestamps();

                $table->index('fb_user_id');
            });
        }

        /**
         * FB PAGES
         */
        if (!Schema::hasTable('fb_pages')) {
            Schema::create('fb_pages', function (Blueprint $table) {
                $table->id();
                $table->string('page_id')->unique();
                $table->string('name')->nullable();
                $table->string('category')->nullable();
                $table->string('avatar_url')->nullable();
                $table->unsignedBigInteger('owner_id')->nullable(); // fb_users.id
                $table->json('permissions')->nullable();
                $table->json('raw')->nullable();
                $table->timestamps();

                $table->index('page_id');
                $table->index('owner_id');
            });
        }

        /**
         * FB PAGE TOKENS (long-lived page token)
         */
        if (!Schema::hasTable('fb_page_tokens')) {
            Schema::create('fb_page_tokens', function (Blueprint $table) {
                $table->id();
                $table->string('page_id');                      // fb_pages.page_id
                $table->text('access_token')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamps();

                $table->index('page_id');
            });
        }

        /**
         * FB POSTS (Gộp cả "create_fb_posts_table" và "alter_fb_posts_add_details")
         * Lưu lịch sử bài đã đăng / phản hồi từ Graph API
         */
        if (!Schema::hasTable('fb_posts')) {
            Schema::create('fb_posts', function (Blueprint $table) {
                $table->id();
                $table->string('page_id');                      // fb_pages.page_id
                $table->string('page_name')->nullable();

                $table->string('post_id')->nullable();          // id bài viết trên FB
                $table->longText('message')->nullable();

                // bổ sung chi tiết
                $table->string('type')->nullable();             // feed|photo|album|video|...
                $table->string('status')->default('published'); // published|error|draft...
                $table->integer('error_code')->nullable();
                $table->text('error_message')->nullable();
                $table->json('response')->nullable();           // Json response FB

                $table->timestamps();

                $table->index('page_id');
                $table->index('post_id');
                $table->index(['page_id', 'post_id']);
            });
        }

        /**
         * SCHEDULED POSTS (Gộp cả "create_scheduled_posts_table" và "alter_scheduled_posts_add_media_paths")
         * Quản lý bài lên lịch
         */
        if (!Schema::hasTable('scheduled_posts')) {
            Schema::create('scheduled_posts', function (Blueprint $table) {
                $table->id();
                $table->string('page_id');                      // fb_pages.page_id
                $table->string('page_name')->nullable();

                $table->longText('message')->nullable();

                // Ảnh cũ (giữ cho tương thích ngược); nếu đăng nhiều ảnh thì để null
                $table->string('media_path')->nullable();

                // Album nhiều ảnh
                $table->json('media_paths')->nullable();        // ["scheduled_media/xxx.jpg", ...]
                $table->string('media_type')->nullable();       // null|photo|album

                // Lịch giờ và TZ
                $table->string('timezone')->default('UTC');     // ví dụ: Asia/Ho_Chi_Minh
                $table->timestamp('publish_at')->nullable();    // LƯU THEO UTC

                // Trạng thái & lỗi
                $table->string('status')->default('queued');    // queued|processing|published|failed|canceled
                $table->integer('error_code')->nullable();
                $table->text('error_message')->nullable();
                $table->json('response')->nullable();

                $table->timestamps();

                $table->index('page_id');
                $table->index('status');
                $table->index('publish_at');
            });
        }

        /**
         * CACHE (chuẩn Laravel, support sqlite/mysql)
         */
        if (!Schema::hasTable('cache')) {
            Schema::create('cache', function (Blueprint $table) {
                $table->string('key')->primary();
                $table->mediumText('value');
                $table->integer('expiration');
            });
        }

        /**
         * JOBS (chuẩn Laravel queue)
         */
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
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('scheduled_posts');
        Schema::dropIfExists('fb_posts');
        Schema::dropIfExists('fb_page_tokens');
        Schema::dropIfExists('fb_pages');
        Schema::dropIfExists('fb_users');
    }
};
