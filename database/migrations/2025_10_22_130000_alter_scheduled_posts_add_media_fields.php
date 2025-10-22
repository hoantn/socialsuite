<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('scheduled_posts', function (Blueprint $table) {
            // JSON trong SQLite sẽ là TEXT, vẫn OK.
            if (!Schema::hasColumn('scheduled_posts', 'media_paths')) {
                $table->json('media_paths')->nullable()->after('message');
            }
            if (!Schema::hasColumn('scheduled_posts', 'media_count')) {
                $table->unsignedTinyInteger('media_count')->default(0)->after('media_paths');
            }
            if (!Schema::hasColumn('scheduled_posts', 'media_type')) {
                // 'image' | 'video'
                $table->string('media_type', 10)->nullable()->after('media_count');
            }
            if (!Schema::hasColumn('scheduled_posts', 'timezone')) {
                $table->string('timezone', 64)->default('Asia/Ho_Chi_Minh')->after('media_type');
            }
            if (!Schema::hasColumn('scheduled_posts', 'publish_at')) {
                $table->dateTime('publish_at')->index()->after('timezone');
            }
            if (!Schema::hasColumn('scheduled_posts', 'status')) {
                $table->string('status', 20)->default('queued')->index()->after('publish_at');
            }
            if (!Schema::hasColumn('scheduled_posts', 'batch_id')) {
                $table->string('batch_id', 64)->nullable()->after('status');
            }
            if (!Schema::hasColumn('scheduled_posts', 'error')) {
                $table->text('error')->nullable()->after('batch_id');
            }
        });
    }

    public function down(): void
    {
        // (SQLite hạn chế drop column; nhánh down chỉ để đối xứng)
        Schema::table('scheduled_posts', function (Blueprint $table) {
            if (Schema::hasColumn('scheduled_posts', 'media_paths'))  $table->dropColumn('media_paths');
            if (Schema::hasColumn('scheduled_posts', 'media_count'))  $table->dropColumn('media_count');
            if (Schema::hasColumn('scheduled_posts', 'media_type'))   $table->dropColumn('media_type');
            if (Schema::hasColumn('scheduled_posts', 'timezone'))     $table->dropColumn('timezone');
            if (Schema::hasColumn('scheduled_posts', 'publish_at'))   $table->dropColumn('publish_at');
            if (Schema::hasColumn('scheduled_posts', 'status'))       $table->dropColumn('status');
            if (Schema::hasColumn('scheduled_posts', 'batch_id'))     $table->dropColumn('batch_id');
            if (Schema::hasColumn('scheduled_posts', 'error'))        $table->dropColumn('error');
        });
    }
};
