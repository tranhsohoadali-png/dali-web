<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Cờ "Tạm hết hàng" cho sản phẩm 3D — để đại lý KHÔNG đặt đơn đi hộ mặt hàng đang hết,
 * mà không cần ẩn hẳn sản phẩm khỏi trang bán.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sp_3d', function (Blueprint $t) {
            if (!Schema::hasColumn('sp_3d', 'tam_het')) {
                $t->boolean('tam_het')->default(false)->after('kho');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sp_3d', function (Blueprint $t) {
            if (Schema::hasColumn('sp_3d', 'tam_het')) $t->dropColumn('tam_het');
        });
    }
};
