<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Danh sách môn để SOẠN TKB cho đơn đi hộ (chốt từ AI đọc ảnh) — [{mon, sl, xong}].
 * Thợ tick từng môn khi làm để tránh thiếu sót.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('don_di_ho', function (Blueprint $t) {
            $t->json('mon_soan')->nullable()->after('chi_tiet');
        });
    }

    public function down(): void
    {
        Schema::table('don_di_ho', function (Blueprint $t) {
            $t->dropColumn('mon_soan');
        });
    }
};
