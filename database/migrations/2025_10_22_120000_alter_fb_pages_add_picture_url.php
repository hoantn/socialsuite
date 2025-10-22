<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('fb_pages', function (Blueprint $table) {
            if (!Schema::hasColumn('fb_pages', 'picture_url')) {
                // Thêm sau 'category' cho dễ nhìn (SQLite sẽ bỏ qua vị trí)
                $table->string('picture_url')->nullable()->after('category');
            }
        });
    }

    public function down(): void
    {
        Schema::table('fb_pages', function (Blueprint $table) {
            if (Schema::hasColumn('fb_pages', 'picture_url')) {
                $table->dropColumn('picture_url');
            }
        });
    }
};
