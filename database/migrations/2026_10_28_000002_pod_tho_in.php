<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Thợ in POD: tài khoản riêng đăng nhập /tho-in, chỉ thấy hàng đợi in
 * (không thấy tiền, kho mẫu, ví seller, phần admin khác).
 * pod_don ghi ai bấm "Bắt đầu in" / "Đã giao".
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pod_tho_in')) {
            Schema::create('pod_tho_in', function (Blueprint $t) {
                $t->id();
                $t->string('ten', 120);
                $t->string('sdt', 20)->unique();
                $t->string('matkhau');
                $t->boolean('hien')->default(true);         // false = khoá
                $t->timestamp('dang_nhap_luc')->nullable();
                $t->timestamps();
            });
        }
        Schema::table('pod_don', function (Blueprint $t) {
            if (!Schema::hasColumn('pod_don', 'nguoi_in'))   $t->string('nguoi_in', 120)->nullable();
            if (!Schema::hasColumn('pod_don', 'nguoi_giao')) $t->string('nguoi_giao', 120)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pod_tho_in');
        Schema::table('pod_don', function (Blueprint $t) {
            $t->dropColumn(['nguoi_in', 'nguoi_giao']);
        });
    }
};
