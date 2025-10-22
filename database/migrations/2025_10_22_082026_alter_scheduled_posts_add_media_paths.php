<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('scheduled_posts', function (Blueprint $table) {
            if (!Schema::hasColumn('scheduled_posts', 'media_paths')) {
                $table->json('media_paths')->nullable()->after('media_path');
            }
            if (!Schema::hasColumn('scheduled_posts', 'media_type')) {
                $table->string('media_type')->nullable()->after('media_paths'); // photo|album
            }
        });
    }

    public function down(): void {
        Schema::table('scheduled_posts', function (Blueprint $table) {
            if (Schema::hasColumn('scheduled_posts', 'media_paths')) {
                $table->dropColumn('media_paths');
            }
            if (Schema::hasColumn('scheduled_posts', 'media_type')) {
                $table->dropColumn('media_type');
            }
        });
    }
};
