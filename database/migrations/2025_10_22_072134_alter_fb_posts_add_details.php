<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (Schema::hasTable('fb_posts')) {
            Schema::table('fb_posts', function (Blueprint $table) {
                if (!Schema::hasColumn('fb_posts', 'page_name')) {
                    $table->string('page_name')->nullable()->after('page_id');
                }
                if (!Schema::hasColumn('fb_posts', 'error_code')) {
                    $table->string('error_code')->nullable()->after('status');
                }
                if (!Schema::hasColumn('fb_posts', 'error_message')) {
                    $table->text('error_message')->nullable()->after('error_code');
                }
            });
        }
    }
    public function down(): void {
        if (Schema::hasTable('fb_posts')) {
            Schema::table('fb_posts', function (Blueprint $table) {
                if (Schema::hasColumn('fb_posts', 'page_name')) {
                    $table->dropColumn('page_name');
                }
                if (Schema::hasColumn('fb_posts', 'error_code')) {
                    $table->dropColumn('error_code');
                }
                if (Schema::hasColumn('fb_posts', 'error_message')) {
                    $table->dropColumn('error_message');
                }
            });
        }
    }
};
