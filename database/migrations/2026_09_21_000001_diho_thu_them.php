<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Chi phí thu thêm cho đơn Đi đơn hộ (admin nhập tay) + ghi chú. Cộng vào tong_si
 * để đối soát ra đúng số tiền phải thu.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('don_di_ho', function (Blueprint $t) {
            $t->integer('thu_them')->default(0)->after('tong_si');
            $t->string('thu_them_gc')->nullable()->after('thu_them');
        });
    }

    public function down(): void
    {
        Schema::table('don_di_ho', function (Blueprint $t) {
            $t->dropColumn(['thu_them', 'thu_them_gc']);
        });
    }
};
