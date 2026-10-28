<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tăng giá riêng từng mẫu (%): đơn giá gram của mẫu = đơn giá chung × (100 + tang_gia) / 100.
 * Vd mẫu khắc tên tốn công dựng file theo từng đơn: tang_gia 20 -> PETG 1.080đ/g, PLA 1.560đ/g.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pod_mau', function (Blueprint $t) {
            if (!Schema::hasColumn('pod_mau', 'tang_gia')) $t->unsignedSmallInteger('tang_gia')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('pod_mau', fn (Blueprint $t) => $t->dropColumn('tang_gia'));
    }
};
