<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('fb_posts')) {
            Schema::create('fb_posts', function (Blueprint $table) {
                $table->id();
                $table->string('page_id')->index();
                $table->string('post_id')->nullable()->index();
                $table->text('message')->nullable();
                $table->string('type')->nullable(); // feed/photo
                $table->string('status')->default('published'); // published|error
                $table->json('response')->nullable();
                $table->timestamps();
            });
        }
    }
    public function down(): void {
        Schema::dropIfExists('fb_posts');
    }
};
