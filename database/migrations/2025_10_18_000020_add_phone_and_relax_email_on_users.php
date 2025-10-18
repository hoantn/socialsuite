<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * [SOCIALSUITE][GPT][2025-10-18 10:33 +07] Add phone; make email nullable; drop unique(email)
 * Cho phép nhiều tài khoản dùng chung email/phone.
 */
return new class extends Migration {
    public function up(): void
    {
        // Thêm cột phone nếu chưa có
        if (!Schema::hasColumn('users', 'phone')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('phone', 32)->nullable()->after('email');
            });
        }

        // Bỏ unique(email) nếu đang tồn tại
        try { Schema::table('users', function (Blueprint $table) { $table->dropUnique('users_email_unique'); }); } catch (\Throwable $e) { /* ignore */ }

        // Cho email nullable
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            Schema::table('users', function (Blueprint $table) {
                $table->string('email')->nullable()->change();
            });
        } else {
            // SQLite: best-effort đảm bảo dữ liệu hợp lệ
            try { DB::statement("UPDATE users SET email = NULL WHERE email = ''"); } catch (\Throwable $e) {}
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'phone')) {
            Schema::table('users', function (Blueprint $table) { $table->dropColumn('phone'); });
        }
        // Không thêm lại unique(email) để tránh khoá dữ liệu ngược.
    }
};
