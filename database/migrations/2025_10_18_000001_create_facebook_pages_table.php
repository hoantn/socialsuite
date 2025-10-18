<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/** [SOCIALSUITE][GPT][2025-10-18 09:40 +07] Create facebook_pages */
return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('facebook_pages')) {
            Schema::create('facebook_pages', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->string('fb_user_id')->index();
                $table->string('page_id')->index();
                $table->string('name')->nullable();
                $table->text('access_token');
                $table->timestamps();
                $table->unique(['fb_user_id','page_id']);
            });
        }
    }
    public function down(): void {
        Schema::dropIfExists('facebook_pages');
    }
};
