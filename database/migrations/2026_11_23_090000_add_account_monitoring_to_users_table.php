<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Akun nonaktif tidak bisa login (dikelola admin).
            $table->boolean('is_active')->default(true)->after('role');
            // 'mandiri' = daftar sendiri lewat halaman registrasi, 'admin' = dibuat admin/seeder.
            $table->string('registered_via', 20)->default('admin')->after('is_active');
            // Bahan pemantauan admin. Kata sandi sendiri TIDAK pernah disimpan polos (hash bcrypt).
            $table->timestamp('last_login_at')->nullable()->after('registered_via');
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
            $table->timestamp('password_changed_at')->nullable()->after('last_login_ip');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'registered_via', 'last_login_at', 'last_login_ip', 'password_changed_at']);
        });
    }
};
