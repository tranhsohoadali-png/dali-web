<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ví trả sau: hạn mức nợ (đ) xưởng cấp cho từng seller. Đơn vẫn vào hàng đợi in khi ví âm,
 * miễn số dư sau khi trừ không thấp hơn −han_muc; seller chuyển khoản sau để trả nợ.
 * 0 = trả trước như cũ (ví không bao giờ âm vì đơn).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pod_seller', function (Blueprint $t) {
            if (!Schema::hasColumn('pod_seller', 'han_muc')) $t->unsignedBigInteger('han_muc')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('pod_seller', fn (Blueprint $t) => $t->dropColumn('han_muc'));
    }
};
