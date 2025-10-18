<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
  public function up(): void {
    if (!Schema::hasTable('facebook_accounts')) {
      Schema::create('facebook_accounts', function (Blueprint $t) {
        $t->id();
        $t->string('fb_user_id')->unique();
        $t->string('name')->nullable();
        $t->string('avatar_url')->nullable();
        $t->text('user_access_token');
        $t->timestamp('expires_at')->nullable();
        $t->timestamps();
      });
    }
  }
  public function down(): void { Schema::dropIfExists('facebook_accounts'); }
};