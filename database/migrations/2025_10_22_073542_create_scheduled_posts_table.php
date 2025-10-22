<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('scheduled_posts')) {
            Schema::create('scheduled_posts', function (Blueprint $table) {
                $table->id();
                $table->string('page_id');
                $table->string('page_name')->nullable();
                $table->text('message')->nullable();
                $table->string('media_path')->nullable();
                $table->string('timezone')->default('UTC');
                $table->timestamp('publish_at'); // stored in UTC
                $table->string('status')->default('queued'); // queued|processing|published|failed|canceled
                $table->string('error_code')->nullable();
                $table->text('error_message')->nullable();
                $table->json('response')->nullable();
                $table->timestamps();

                $table->index(['status','publish_at']);
                $table->index('page_id');
            });
        }
    }

    public function down(): void {
        Schema::dropIfExists('scheduled_posts');
    }
};
