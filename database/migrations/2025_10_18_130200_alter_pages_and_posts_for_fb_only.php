<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
  public function up(): void {
    if (Schema::hasTable('facebook_pages')) {
      Schema::table('facebook_pages', function (Blueprint $t) {
        if (!Schema::hasColumn('facebook_pages','page_id')) $t->string('page_id')->nullable()->after('id');
        try { $t->unique('page_id'); } catch (\Throwable $e) {}
        if (Schema::hasColumn('facebook_pages','user_id')) { try { $t->index('user_id'); } catch (\Throwable $e) {} }
      });
    }
    if (Schema::hasTable('posts') && !Schema::hasColumn('posts','page_membership_id')) {
      Schema::table('posts', function (Blueprint $t) {
        $t->foreignId('page_membership_id')->nullable()->after('facebook_page_id')
          ->constrained('page_memberships')->nullOnDelete();
      });
    }
  }
  public function down(): void {
    if (Schema::hasTable('posts') && Schema::hasColumn('posts','page_membership_id')) {
      Schema::table('posts', function (Blueprint $t) { $t->dropConstrainedForeignId('page_membership_id'); });
    }
    if (Schema::hasTable('facebook_pages')) {
      Schema::table('facebook_pages', function (Blueprint $t) { try { $t->dropUnique(['page_id']); } catch (\Throwable $e) {} });
    }
  }
};