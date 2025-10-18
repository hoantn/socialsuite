<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('page_id');
            $table->string('fb_post_id')->nullable();
            $table->text('message')->nullable();
            $table->string('status')->default('published'); // published|scheduled
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamps();
            $table->index(['user_id','page_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('posts'); }
};
