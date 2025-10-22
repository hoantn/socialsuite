<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('scheduled_posts', function (Blueprint $table) {
            if (!Schema::hasColumn('scheduled_posts', 'media_count')) {
                $table->unsignedTinyInteger('media_count')->default(0)->after('media_type');
            }
            if (!Schema::hasColumn('scheduled_posts', 'timezone')) {
                $table->string('timezone', 64)->default('Asia/Ho_Chi_Minh')->after('publish_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('scheduled_posts', function (Blueprint $table) {
            if (Schema::hasColumn('scheduled_posts', 'media_count')) {
                $table->dropColumn('media_count');
            }
            if (Schema::hasColumn('scheduled_posts', 'timezone')) {
                $table->dropColumn('timezone');
            }
        });
    }
};
