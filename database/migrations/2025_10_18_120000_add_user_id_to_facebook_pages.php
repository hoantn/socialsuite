<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/**
 * [SOCIALSUITE][GPT][2025-10-18 11:12 +07] Add user_id to facebook_pages and index it.
 */
return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('facebook_pages') && !Schema::hasColumn('facebook_pages','user_id')) {
            Schema::table('facebook_pages', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id')->index();
            });
        }
    }
    public function down(): void
    {
        if (Schema::hasTable('facebook_pages') && Schema::hasColumn('facebook_pages','user_id')) {
            Schema::table('facebook_pages', function (Blueprint $table) {
                $table->dropIndex(['user_id']);
                $table->dropColumn('user_id');
            });
        }
    }
};
