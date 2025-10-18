<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Thêm username nếu chưa có
        if (!Schema::hasColumn('users', 'username')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('username')->unique()->after('name');
            });
        }

        // Cho email nullable (đăng ký không bắt buộc email)
        // MySQL hỗ trợ change(); SQLite thì dùng raw SQL đơn giản
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            Schema::table('users', function (Blueprint $table) {
                $table->string('email')->nullable()->unique()->change();
            });
        } else {
            // SQLite: thử đổi cột email thành NULLABLE nếu đang NOT NULL
            try {
                DB::statement("UPDATE users SET email = NULL WHERE email = ''");
                DB::statement("PRAGMA foreign_keys=off");
                DB::statement("CREATE TABLE users_tmp AS SELECT id, name, username, email, email_verified_at, password, remember_token, created_at, updated_at FROM users");
                DB::statement("DROP TABLE users");
                DB::statement("CREATE TABLE users (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    name VARCHAR(255) NULL,
                    username VARCHAR(255) NOT NULL UNIQUE,
                    email VARCHAR(255) NULL,
                    email_verified_at DATETIME NULL,
                    password VARCHAR(255) NOT NULL,
                    remember_token VARCHAR(100) NULL,
                    created_at DATETIME NULL,
                    updated_at DATETIME NULL
                )");
                DB::statement("INSERT INTO users (id, name, username, email, email_verified_at, password, remember_token, created_at, updated_at)
                               SELECT id, name, username, email, email_verified_at, password, remember_token, created_at, updated_at FROM users_tmp");
                DB::statement("DROP TABLE users_tmp");
                DB::statement("PRAGMA foreign_keys=on");
            } catch (\Throwable $e) {
                // Nếu SQLite bản của bạn không cần recreate, bỏ qua
            }
        }
    }

    public function down(): void
    {
        // Down đơn giản: xoá cột username (nếu cần)
        if (Schema::hasColumn('users', 'username')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropUnique(['username']);
                $table->dropColumn('username');
            });
        }
        // Không ép buộc email NOT NULL lại để tránh mất dữ liệu
    }
};
