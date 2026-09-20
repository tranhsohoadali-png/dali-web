<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Đại lý tự XÁC NHẬN "đơn vị vận chuyển đã nhận hàng thành công" — để xưởng biết
 * đơn đã được VC lấy, khép vòng giao hàng cho đơn Đi đơn hộ.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('don_di_ho', function (Blueprint $t) {
            $t->boolean('dai_ly_xac_nhan')->default(false)->after('gui_luc');
            $t->timestamp('xac_nhan_luc')->nullable()->after('dai_ly_xac_nhan');
        });
    }

    public function down(): void
    {
        Schema::table('don_di_ho', function (Blueprint $t) {
            $t->dropColumn(['dai_ly_xac_nhan', 'xac_nhan_luc']);
        });
    }
};
