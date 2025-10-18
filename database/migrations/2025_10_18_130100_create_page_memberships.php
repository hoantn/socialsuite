<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
  public function up(): void {
    if (!Schema::hasTable('page_memberships')) {
      Schema::create('page_memberships', function (Blueprint $t) {
        $t->id();
        $t->foreignId('facebook_account_id')->constrained('facebook_accounts')->cascadeOnDelete();
        $t->foreignId('facebook_page_id')->constrained('facebook_pages')->cascadeOnDelete();
        $t->text('page_access_token');
        $t->json('perms')->nullable();
        $t->boolean('is_active')->default(true);
        $t->timestamp('last_verified_at')->nullable();
        $t->timestamps();
        $t->unique(['facebook_account_id','facebook_page_id']);
      });
    }
  }
  public function down(): void { Schema::dropIfExists('page_memberships'); }
};