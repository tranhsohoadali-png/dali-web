<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Seller tự đăng ký trên pod.tranhdali.vn/dang-ky: tài khoản tạo ở trạng thái CHỜ DUYỆT
 * (hien=false, cho_duyet=true). Xưởng bấm Duyệt trong admin thì mới đăng nhập được.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pod_seller', function (Blueprint $t) {
            if (!Schema::hasColumn('pod_seller', 'cho_duyet')) $t->boolean('cho_duyet')->default(false);
            if (!Schema::hasColumn('pod_seller', 'san_ban'))   $t->string('san_ban', 120)->nullable();   // Etsy, Amazon, TikTok Shop…
            if (!Schema::hasColumn('pod_seller', 'link_shop')) $t->string('link_shop', 300)->nullable();
            if (!Schema::hasColumn('pod_seller', 'loi_nhan'))  $t->string('loi_nhan', 1000)->nullable(); // sản phẩm định bán, số đơn dự kiến…
        });
    }

    public function down(): void
    {
        Schema::table('pod_seller', function (Blueprint $t) {
            $t->dropColumn(['cho_duyet', 'san_ban', 'link_shop', 'loi_nhan']);
        });
    }
};
