<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::table('pages', function (Blueprint $t) {
      $t->json('perms')->nullable();
      $t->boolean('subscribed')->default(false);
    });
  }
  public function down(): void {
    Schema::table('pages', function (Blueprint $t) {
      $t->dropColumn(['perms','subscribed']);
    });
  }
};