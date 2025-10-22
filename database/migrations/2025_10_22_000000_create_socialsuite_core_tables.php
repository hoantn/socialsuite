<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ---- users (optional safeguard) ----
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('email')->nullable()->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password')->nullable();
                $table->rememberToken();
                $table->timestamps();
            });
        }

        // ---- cache (optional safeguard) ----
        if (!Schema::hasTable('cache')) {
            Schema::create('cache', function (Blueprint $table) {
                $table->string('key')->primary();
                $table->mediumText('value');
                $table->integer('expiration');
            });
        }
        if (!Schema::hasTable('cache_locks')) {
            Schema::create('cache_locks', function (Blueprint $table) {
                $table->string('key')->primary();
                $table->string('owner');
                $table->integer('expiration');
            });
        }

        // ---- jobs (optional safeguard) ----
        if (!Schema::hasTable('jobs')) {
            Schema::create('jobs', function (Blueprint $table) {
                $table->id();
                $table->string('queue')->index();
                $table->longText('payload');
                $table->unsignedTinyInteger('attempts');
                $table->unsignedInteger('reserved_at')->nullable();
                $table->unsignedInteger('available_at');
                $table->unsignedInteger('created_at');
            });
        }
        if (!Schema::hasTable('job_batches')) {
            Schema::create('job_batches', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->string('name');
                $table->integer('total_jobs');
                $table->integer('pending_jobs');
                $table->integer('failed_jobs');
                $table->longText('failed_job_ids');
                $table->mediumText('options')->nullable();
                $table->unsignedInteger('cancelled_at')->nullable();
                $table->unsignedInteger('created_at');
                $table->unsignedInteger('finished_at')->nullable();
            });
        }

        // ---- fb_users ----
        if (!Schema::hasTable('fb_users')) {
            Schema::create('fb_users', function (Blueprint $table) {
                $table->id();
                $table->string('fb_user_id')->unique();
                $table->string('name')->nullable();
                $table->string('email')->nullable();
                $table->string('picture_url')->nullable();
                $table->text('access_token')->nullable();
                $table->timestamp('access_token_expires_at')->nullable();
                $table->json('raw')->nullable();
                $table->timestamps();
            });
        }

        // ---- fb_pages ----
        if (!Schema::hasTable('fb_pages')) {
            Schema::create('fb_pages', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('owner_id')->index();
                $table->string('page_id')->unique();
                $table->string('name')->nullable();
                $table->string('category')->nullable();
                $table->string('picture_url')->nullable();
                $table->string('connected_ig_id')->nullable();
                $table->json('raw')->nullable();
                $table->timestamps();
            });
        }

        // ---- fb_page_tokens ----
        if (!Schema::hasTable('fb_page_tokens')) {
            Schema::create('fb_page_tokens', function (Blueprint $table) {
                $table->id();
                $table->string('page_id')->index();
                $table->text('access_token')->nullable();
                $table->timestamp('access_token_expires_at')->nullable();
                $table->json('raw')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Only drop our feature tables to be safe
        Schema::dropIfExists('fb_page_tokens');
        Schema::dropIfExists('fb_pages');
        Schema::dropIfExists('fb_users');
    }
};
