<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Phụ phí "in tên riêng" theo TỪNG mã sản phẩm — dùng cho module Đi đơn hộ:
 * đại lý tích ô in tên → cộng phụ phí này MỘT LẦN cho dòng đó. 0 = không thu.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('sp_3d', function (Blueprint $t) {
            $t->unsignedInteger('phu_phi_ten')->default(0)->after('sll_tu');
        });
    }

    public function down(): void
    {
        Schema::table('sp_3d', function (Blueprint $t) {
            $t->dropColumn('phu_phi_ten');
        });
    }
};
