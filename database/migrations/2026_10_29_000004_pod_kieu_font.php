<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Một sản phẩm nhiều kiểu (vd ornament 5 kiểu khung): phân loại = kiểu × cỡ × nhựa.
 * pod_bien_the.kieu = khoá kiểu, khớp xem_truoc.kieu[<khoá>] (khung + ô tên riêng từng kiểu).
 * Dòng đơn lưu font seller chọn (mẫu khắc tên cho chọn trong xem_truoc.fonts).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pod_bien_the', function (Blueprint $t) {
            if (!Schema::hasColumn('pod_bien_the', 'kieu')) $t->string('kieu', 60)->nullable();
        });
        Schema::table('pod_don_dong', function (Blueprint $t) {
            if (!Schema::hasColumn('pod_don_dong', 'font')) $t->string('font', 60)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pod_bien_the', fn (Blueprint $t) => $t->dropColumn('kieu'));
        Schema::table('pod_don_dong', fn (Blueprint $t) => $t->dropColumn('font'));
    }
};
