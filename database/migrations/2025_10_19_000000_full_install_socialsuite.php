<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Ghi chú:
     * - Migration hợp nhất, chạy được trên SQLite để bạn dev nhanh.
     * - Có kiểm tra Schema::hasTable trước khi tạo, tránh lỗi "table already exists".
     * - Nếu bạn muốn “làm sạch” DB, khuyên dùng: php artisan migrate:fresh
     */

    public function up(): void
    {
        // Bật/tắt khóa ngoại an toàn cho nhiều driver (SQLite cần đặc biệt)
        Schema::disableForeignKeyConstraints();

        /*
        |--------------------------------------------------------------------------
        | Core tables: users, cache, jobs
        |--------------------------------------------------------------------------
        */

        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                // Với SQLite dev: dùng increments thay vì bigIncrements để đơn giản hóa
                $table->increments('id');
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('cache')) {
            Schema::create('cache', function (Blueprint $table) {
                $table->string('key')->primary();
                $table->mediumText('value');
                $table->integer('expiration');
            });
        }

        if (!Schema::hasTable('jobs')) {
            Schema::create('jobs', function (Blueprint $table) {
                $table->increments('id');
                $table->string('queue')->index();
                $table->longText('payload');
                $table->unsignedTinyInteger('attempts');
                $table->unsignedInteger('reserved_at')->nullable();
                $table->unsignedInteger('available_at');
                $table->unsignedInteger('created_at');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Social/Bot tables
        |--------------------------------------------------------------------------
        | Lưu ý: 1 FB account có thể quản lý nhiều Page; 1 Page có thể có nhiều account.
        | Mọi config nên bám theo page_id để không phụ thuộc user.
        */

        if (!Schema::hasTable('pages')) {
            Schema::create('pages', function (Blueprint $table) {
                $table->increments('id'); // internal id
                $table->string('page_id')->unique(); // id page từ Facebook
                $table->string('name')->nullable();
                $table->text('page_access_token')->nullable();
                $table->text('perms')->nullable(); // json các quyền đã cấp, theo dõi nhanh
                $table->timestamps();
            });
        }

        // Quan hệ nhiều-nhiều giữa users và pages + metadata
        if (!Schema::hasTable('page_users')) {
            Schema::create('page_users', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('user_id');
                $table->unsignedInteger('page_id'); // foreign -> pages.id (internal key)
                $table->string('role')->nullable(); // admin/editor..., tùy theo business

                $table->timestamps();

                $table->index(['user_id', 'page_id']);

                // Ràng buộc khóa ngoại an toàn cho SQLite
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
            });
        }

        // Lưu tokens: của user và/hoặc page. Mỗi dòng một token theo loại (page|user)
        if (!Schema::hasTable('fb_tokens')) {
            Schema::create('fb_tokens', function (Blueprint $table) {
                $table->increments('id');

                $table->unsignedInteger('user_id')->nullable(); // người sở hữu token (nếu cần)
                $table->unsignedInteger('page_id')->nullable(); // token gắn với page (nếu là page_token)

                $table->enum('type', ['user', 'page'])->default('user');
                $table->text('token');               // access token
                $table->timestamp('expires_at')->nullable(); // user_token có thể hết hạn
                $table->text('scopes')->nullable(); // json list scopes

                $table->timestamps();

                $table->index(['user_id', 'page_id', 'type']);

                // FK an toàn
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
            });
        }

        // Webhook đăng ký với FB theo từng page
        if (!Schema::hasTable('webhooks')) {
            Schema::create('webhooks', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('page_id');           // pages.id
                $table->string('verify_token')->nullable();    // để verify
                $table->string('callback_url')->nullable();    // url đăng ký
                $table->string('object')->default('page');     // 'page' là chính
                $table->boolean('is_subscribed')->default(false);
                $table->json('fields')->nullable();            // json các field đã subscribe
                $table->timestamps();

                $table->index('page_id');
                $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
            });
        }

        // Conversations (mức cơ bản để bạn mở rộng Inbox/Flows về sau)
        if (!Schema::hasTable('conversations')) {
            Schema::create('conversations', function (Blueprint $table) {
                $table->increments('id');

                $table->unsignedInteger('page_id'); // page sở hữu cuộc hội thoại
                $table->string('psid')->index();    // Page-Scoped ID của user phía FB
                $table->string('source')->nullable(); // 'inbox' | 'comment' | 'live'...

                $table->string('last_message')->nullable();
                $table->timestamp('last_interacted_at')->nullable();

                $table->timestamps();

                $table->index(['page_id', 'psid']);
                $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
            });
        }

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        // Xóa theo thứ tự phụ thuộc
        if (Schema::hasTable('conversations')) {
            Schema::drop('conversations');
        }
        if (Schema::hasTable('webhooks')) {
            Schema::drop('webhooks');
        }
        if (Schema::hasTable('fb_tokens')) {
            Schema::drop('fb_tokens');
        }
        if (Schema::hasTable('page_users')) {
            Schema::drop('page_users');
        }
        if (Schema::hasTable('pages')) {
            Schema::drop('pages');
        }

        if (Schema::hasTable('jobs')) {
            Schema::drop('jobs');
        }
        if (Schema::hasTable('cache')) {
            Schema::drop('cache');
        }
        if (Schema::hasTable('users')) {
            Schema::drop('users');
        }

        Schema::enableForeignKeyConstraints();
    }
};
