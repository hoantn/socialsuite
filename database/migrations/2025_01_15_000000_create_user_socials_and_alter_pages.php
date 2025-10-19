<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    if (!Schema::hasTable('user_socials')) {
      Schema::create('user_socials', function (Blueprint $t) {
        $t->id();
        $t->unsignedBigInteger('user_id');
        $t->string('provider');
        $t->text('access_token');
        $t->boolean('long_lived')->default(false);
        $t->timestamp('expires_at')->nullable();
        $t->timestamps();
        $t->index(['user_id','provider']);
      });
    }
    Schema::table('pages', function (Blueprint $t) {
      if (!Schema::hasColumn('pages','token_expires_at')) $t->timestamp('token_expires_at')->nullable();
    });
  }
  public function down(): void {
    Schema::dropIfExists('user_socials');
    Schema::table('pages', function (Blueprint $t) {
      if (Schema::hasColumn('pages','token_expires_at')) $t->dropColumn('token_expires_at');
    });
  }
};