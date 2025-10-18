<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * SOCIALSUITE - FULL SCHEMA (FACEBOOK-ONLY) RESET
 * - Xoá sạch các bảng user/legacy nếu có
 * - Tạo mới toàn bộ schema chỉ dùng đăng nhập Facebook
 */
return new class extends Migration {
    public function up(): void
    {
        // DROP legacy tables if exist (safe)
        $dropIfExists = [
            'users','password_resets','password_reset_tokens',
            'personal_access_tokens','failed_jobs','jobs','job_batches',
            'oauth_access_tokens','oauth_auth_codes','oauth_clients','oauth_personal_access_clients','oauth_refresh_tokens',
            'teams','team_user',
            'facebook_pages','facebook_accounts','page_memberships','posts'
        ];
        foreach ($dropIfExists as $t) {
            if (Schema::hasTable($t)) Schema::drop($t);
        }

        // facebook_accounts
        Schema::create('facebook_accounts', function (Blueprint $t) {
            $t->id();
            $t->string('fb_user_id')->unique();
            $t->string('name')->nullable();
            $t->string('avatar_url')->nullable();
            $t->text('user_access_token');
            $t->string('refresh_token')->nullable();
            $t->timestamp('expires_at')->nullable();
            $t->timestamps();
        });

        // facebook_pages
        Schema::create('facebook_pages', function (Blueprint $t) {
            $t->id();
            $t->string('page_id')->unique();
            $t->string('name');
            $t->string('picture_url')->nullable();
            $t->string('category')->nullable();
            $t->timestamps();
        });

        // page_memberships
        Schema::create('page_memberships', function (Blueprint $t) {
            $t->id();
            $t->foreignId('facebook_account_id')->constrained('facebook_accounts')->cascadeOnDelete();
            $t->foreignId('facebook_page_id')->constrained('facebook_pages')->cascadeOnDelete();
            $t->string('role')->nullable();
            $t->text('page_access_token')->nullable();
            $t->json('perms')->nullable();
            $t->boolean('is_active')->default(true);
            $t->timestamp('last_verified_at')->nullable();
            $t->timestamps();
            $t->unique(['facebook_account_id','facebook_page_id'],'uniq_membership');
        });

        // posts
        Schema::create('posts', function (Blueprint $t) {
            $t->id();
            $t->foreignId('facebook_page_id')->constrained('facebook_pages')->cascadeOnDelete();
            $t->foreignId('page_membership_id')->nullable()->constrained('page_memberships')->nullOnDelete();
            $t->string('type')->default('text');
            $t->text('message')->nullable();
            $t->string('link')->nullable();
            $t->string('image_url')->nullable();
            $t->timestamp('scheduled_at')->nullable();
            $t->string('status')->default('draft');
            $t->string('fb_post_id')->nullable();
            $t->text('error')->nullable();
            $t->timestamps();
            $t->index(['facebook_page_id','scheduled_at']);
        });
    }

    public function down(): void
    {
        foreach (['posts','page_memberships','facebook_pages','facebook_accounts'] as $t) {
            if (Schema::hasTable($t)) Schema::drop($t);
        }
    }
};
