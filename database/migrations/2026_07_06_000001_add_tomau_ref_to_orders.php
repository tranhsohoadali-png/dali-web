<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Thêm cột tomau_ref: mã CTV của site anh em tomau.tranhdali.vn mang sang qua
 * ?tref=<mã>. KHÔNG dùng affiliate_code (đó là CTV nội bộ tranhdali). Đơn có
 * tomau_ref sẽ được báo về cổng CTV tomau để trả hoa hồng chéo.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('tomau_ref')->nullable()->after('affiliate_commission');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('tomau_ref');
        });
    }
};
