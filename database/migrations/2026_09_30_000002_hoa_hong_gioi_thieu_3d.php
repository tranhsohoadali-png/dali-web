<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Hoa hồng giới thiệu cho khu 3D (tái dùng bảng affiliates + withdrawals sẵn có):
 *  - Link 3d.tranhdali.vn?ref=MÃ: khách lẻ mua qua link -> người chia hưởng commission_rate % (mặc định 5).
 *  - Đại lý mới đăng ký bằng mã -> người giới thiệu hưởng rate_tuyen_duoi % (mặc định 3) doanh số sỉ
 *    đi đơn hộ của đại lý đó. CHỈ 1 CẤP.
 * Hoa hồng chỉ cộng khi xưởng đã thu tiền; rời trạng thái đó (huỷ / bỏ đánh dấu / xoá) thì tự trừ lại.
 * Cờ *_da_cong giữ việc cộng/trừ idempotent.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('affiliates', function (Blueprint $t) {
            if (!Schema::hasColumn('affiliates', 'rate_tuyen_duoi')) {
                $t->decimal('rate_tuyen_duoi', 5, 2)->default(3.00)->after('commission_rate');
            }
        });

        Schema::table('dai_ly', function (Blueprint $t) {
            if (!Schema::hasColumn('dai_ly', 'affiliate_id'))      $t->unsignedBigInteger('affiliate_id')->nullable()->index();      // ví hoa hồng + mã giới thiệu của chính đại lý
            if (!Schema::hasColumn('dai_ly', 'gioi_thieu_aff_id')) $t->unsignedBigInteger('gioi_thieu_aff_id')->nullable()->index(); // ai giới thiệu đại lý này (affiliate id)
            if (!Schema::hasColumn('dai_ly', 'cho_duyet'))         $t->boolean('cho_duyet')->default(false);                         // tự đăng ký, chờ xưởng duyệt
        });

        Schema::table('don_3d', function (Blueprint $t) {
            if (!Schema::hasColumn('don_3d', 'ref_code'))         $t->string('ref_code', 40)->nullable()->index();
            if (!Schema::hasColumn('don_3d', 'hoa_hong'))         $t->unsignedInteger('hoa_hong')->default(0);
            if (!Schema::hasColumn('don_3d', 'hoa_hong_aff_id'))  $t->unsignedBigInteger('hoa_hong_aff_id')->nullable()->index();
            if (!Schema::hasColumn('don_3d', 'hoa_hong_da_cong')) $t->boolean('hoa_hong_da_cong')->default(false);
        });

        Schema::table('don_di_ho', function (Blueprint $t) {
            if (!Schema::hasColumn('don_di_ho', 'hoa_hong'))         $t->unsignedInteger('hoa_hong')->default(0);
            if (!Schema::hasColumn('don_di_ho', 'hoa_hong_aff_id'))  $t->unsignedBigInteger('hoa_hong_aff_id')->nullable()->index();
            if (!Schema::hasColumn('don_di_ho', 'hoa_hong_da_cong')) $t->boolean('hoa_hong_da_cong')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('don_di_ho', fn (Blueprint $t) => $t->dropColumn(['hoa_hong', 'hoa_hong_aff_id', 'hoa_hong_da_cong']));
        Schema::table('don_3d', fn (Blueprint $t) => $t->dropColumn(['ref_code', 'hoa_hong', 'hoa_hong_aff_id', 'hoa_hong_da_cong']));
        Schema::table('dai_ly', fn (Blueprint $t) => $t->dropColumn(['affiliate_id', 'gioi_thieu_aff_id', 'cho_duyet']));
        Schema::table('affiliates', fn (Blueprint $t) => $t->dropColumn('rate_tuyen_duoi'));
    }
};
