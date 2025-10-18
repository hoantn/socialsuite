<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * [SOCIALSUITE][GPT][2025-10-18 17:05 +07]
 * CHANGE: Tạo full schema cho SocialSuite trong 1 migration duy nhất (DB sạch).
 * WHY: Tránh xung đột "already exists" khi có sẵn dump SQL hoặc migrations mặc định.
 * IMPACT: Tạo các bảng: users, password_reset_tokens, failed_jobs, personal_access_tokens, facebook_tokens.
 * TEST: php artisan migrate => tạo đủ 5 bảng; php artisan migrate:fresh => chạy lại sạch.
 * ROLLBACK: php artisan migrate:rollback (sẽ drop toàn bộ bảng do file này tạo).
 */
return new class extends Migration
{
    public function up(): void
    {
        // USERS
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id(); // BIGINT UNSIGNED AUTO_INCREMENT
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
            });
        }

        // PASSWORD RESET TOKENS (Laravel 10/11)
        if (!Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }

        // FAILED JOBS
        if (!Schema::hasTable('failed_jobs')) {
            Schema::create('failed_jobs', function (Blueprint $table) {
                $table->id();
                $table->string('uuid')->unique();
                $table->text('connection');
                $table->text('queue');
                $table->longText('payload');
                $table->longText('exception');
                $table->timestamp('failed_at')->useCurrent();
            });
        }

        // PERSONAL ACCESS TOKENS (Sanctum/Passport-like)
        if (!Schema::hasTable('personal_access_tokens')) {
            Schema::create('personal_access_tokens', function (Blueprint $table) {
                $table->id();
                $table->morphs('tokenable'); // tokenable_type, tokenable_id (BIGINT)
                $table->string('name');
                $table->string('token', 64)->unique();
                $table->text('abilities')->nullable();
                $table->timestamp('last_used_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamps();
            });
        }

        // FACEBOOK TOKENS (long-lived user token)
        if (!Schema::hasTable('facebook_tokens')) {
            Schema::create('facebook_tokens', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->string('fb_user_id')->index();
                $table->string('fb_name')->nullable();
                $table->text('token');                // long-lived user access token
                $table->timestamp('expires_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Drop theo thứ tự tránh phụ thuộc
        Schema::dropIfExists('facebook_tokens');
        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
