<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Ví trả sau KHÔNG GIỚI HẠN (chủ xưởng 11-16): pod_seller.tra_sau bật thì đơn luôn vào hàng đợi in, ví âm thành nợ,
 * seller chuyển khoản sau để trả. Thay hạn mức tiền (pod_seller.han_muc, cột giữ lại nhưng không dùng nữa):
 * seller đang có hạn mức > 0 chuyển thành trả sau.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pod_seller', function (Blueprint $t) {
            if (!Schema::hasColumn('pod_seller', 'tra_sau')) $t->boolean('tra_sau')->default(false);
        });
        if (Schema::hasColumn('pod_seller', 'han_muc')) DB::table('pod_seller')->where('han_muc', '>', 0)->update(['tra_sau' => true]);
    }

    public function down(): void
    {
        Schema::table('pod_seller', fn (Blueprint $t) => $t->dropColumn('tra_sau'));
    }
};
