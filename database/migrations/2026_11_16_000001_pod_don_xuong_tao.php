<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * pod_don.xuong_tao: đơn do XƯỞNG tạo hộ seller ở trang admin (seller gửi đơn qua Zalo...).
 * Tiền vẫn trừ ví seller như đơn seller tự đặt; cổng seller ghi "Xưởng tạo hộ".
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pod_don', function (Blueprint $t) {
            if (!Schema::hasColumn('pod_don', 'xuong_tao')) $t->boolean('xuong_tao')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('pod_don', fn (Blueprint $t) => $t->dropColumn('xuong_tao'));
    }
};
