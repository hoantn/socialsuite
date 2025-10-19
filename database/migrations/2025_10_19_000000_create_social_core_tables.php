
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('social_accounts', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->string('provider');
            $t->string('provider_user_id');
            $t->text('access_token');
            $t->text('refresh_token')->nullable();
            $t->timestamp('token_expires_at')->nullable();
            $t->json('raw')->nullable();
            $t->timestamps();
            $t->unique(['provider','provider_user_id']);
        });

        Schema::create('pages', function (Blueprint $t) {
            $t->id();
            $t->string('provider_page_id')->unique();
            $t->string('name')->nullable();
            $t->string('category')->nullable();
            $t->text('page_access_token')->nullable();
            $t->boolean('subscribed')->default(false);
            $t->timestamps();
        });

        Schema::create('page_user', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->foreignId('page_id')->constrained('pages')->cascadeOnDelete();
            $t->string('role')->nullable();
            $t->timestamps();
            $t->unique(['user_id','page_id']);
        });

        Schema::create('page_configs', function (Blueprint $t) {
            $t->id();
            $t->foreignId('page_id')->constrained('pages')->cascadeOnDelete();
            $t->string('key');
            $t->text('value')->nullable();
            $t->timestamps();
            $t->unique(['page_id','key']);
        });

        Schema::create('webhook_logs', function (Blueprint $t) {
            $t->id();
            $t->string('provider');
            $t->string('type')->nullable();
            $t->json('payload')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('webhook_logs');
        Schema::dropIfExists('page_configs');
        Schema::dropIfExists('page_user');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('social_accounts');
    }
};
