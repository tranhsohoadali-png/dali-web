<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Đánh dấu đã đối soát / thu tiền cho đơn Đi đơn hộ — để trang Đối soát đại lý
 * tách "chưa thu" và "đã thu", tính đúng số tiền còn phải thu của mỗi đại lý.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('don_di_ho', function (Blueprint $t) {
            $t->boolean('da_thanh_toan')->default(false)->index()->after('tt');
            $t->timestamp('thanh_toan_luc')->nullable()->after('da_thanh_toan');
        });
    }

    public function down(): void
    {
        Schema::table('don_di_ho', function (Blueprint $t) {
            $t->dropColumn(['da_thanh_toan', 'thanh_toan_luc']);
        });
    }
};
