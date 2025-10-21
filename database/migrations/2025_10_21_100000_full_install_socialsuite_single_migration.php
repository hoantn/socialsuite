<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /* -------------------- Core FB account/page mapping -------------------- */
        if (!Schema::hasTable('fb_accounts')) {
            Schema::create('fb_accounts', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('fb_user_id')->unique();
                $table->string('name')->nullable();
                $table->string('avatar_url')->nullable();
                $table->longText('user_access_token')->nullable();
                $table->dateTime('token_expires_at')->nullable();
                $table->json('granted_scopes')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('fb_pages')) {
            Schema::create('fb_pages', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('fb_page_id')->unique();
                $table->string('name')->nullable();
                $table->string('avatar_url')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('account_page')) {
            Schema::create('account_page', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('fb_account_id');
                $table->unsignedBigInteger('fb_page_id');
                $table->longText('page_access_token')->nullable();
                $table->json('perms')->nullable();   // perms/tasks from Graph
                $table->json('tasks')->nullable();
                $table->boolean('active')->default(true);
                $table->timestamps();
                $table->unique(['fb_account_id', 'fb_page_id']);
            });
        }

        if (!Schema::hasTable('page_configs')) {
            Schema::create('page_configs', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('fb_page_id');   // references fb_pages.id
                $table->json('settings')->nullable();       // auto_reply, schedules, etc.
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->timestamps();
                $table->unique(['fb_page_id']);
            });
        }

        /* -------------------- Tokens (optional) -------------------- */
        if (!Schema::hasTable('fb_tokens')) {
            Schema::create('fb_tokens', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('fb_account_id')->nullable();
                $table->string('type', 20)->default('user'); // user|page
                $table->longText('token');
                $table->dateTime('expires_at')->nullable();
                $table->timestamps();
                $table->index(['fb_account_id', 'type']);
            });
        }

        /* -------------------- Conversations & Messages -------------------- */
        if (!Schema::hasTable('conversations')) {
            Schema::create('conversations', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('fb_page_id')->nullable(); // link to fb_pages.id
                $table->string('psid')->index();                       // user PSID
                $table->text('last_message')->nullable();
                $table->dateTime('last_at')->nullable();
                $table->timestamps();
                $table->unique(['fb_page_id', 'psid']);
            });
        }

        if (!Schema::hasTable('messages')) {
            Schema::create('messages', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('conversation_id')->index();
                $table->string('direction', 10)->index(); // in|out
                $table->longText('content')->nullable();
                $table->string('fb_message_id')->nullable();
                $table->json('raw')->nullable();
                $table->timestamps();
            });
        }

        /* -------------------- Webhooks store (optional log) -------------------- */
        if (!Schema::hasTable('webhooks')) {
            Schema::create('webhooks', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('page_id')->nullable();
                $table->longText('payload')->nullable();
                $table->dateTime('received_at')->nullable();
                $table->timestamps();
            });
        }

        /* -------------------- Laravel sessions (if using SESSION_DRIVER=database) -------------------- */
        if (!Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        }

        /* -------------------- Queue tables -------------------- */
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
    }

    public function down(): void
    {
        // drop in reverse order (safe if some tables do not exist)
        foreach (['failed_jobs','jobs','sessions','webhooks','messages','conversations',
                  'fb_tokens','page_configs','account_page','fb_pages','fb_accounts'] as $table) {
            if (Schema::hasTable($table)) {
                Schema::drop($table);
            }
        }
    }
};
