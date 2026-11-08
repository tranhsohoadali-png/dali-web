<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Nâng cấp tài khoản seller (chủ xưởng 2026-11-08):
 * - pod_nhan_vien: tài khoản phụ cho nhân viên/VA của seller — đặt đơn được, KHÔNG xem/nạp ví, không quản lý nhân viên.
 *   Đăng nhập cùng màn hình bằng số điện thoại riêng (không trùng số của seller nào).
 * - pod_phien.nhan_vien_id: phiên của nhân viên (null = chủ shop).
 * - pod_don.nhan_vien_id: ai đặt đơn (null = chủ shop).
 * - pod_seller.noi_giao_mac_dinh: nơi giao chọn sẵn khi soạn đơn. pod_seller.doi_mk_luc: lần đổi mật khẩu gần nhất.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pod_nhan_vien')) {
            Schema::create('pod_nhan_vien', function (Blueprint $t) {
                $t->id();
                $t->unsignedBigInteger('seller_id')->index();
                $t->string('ten', 120);
                $t->string('sdt', 20)->unique();
                $t->string('matkhau');
                $t->boolean('hien')->default(true);         // false = chủ shop đã khoá
                $t->timestamp('dong_y_luc')->nullable();    // nhân viên tự đồng ý điều khoản bảo mật
                $t->timestamp('dang_nhap_luc')->nullable();
                $t->timestamp('doi_mk_luc')->nullable();
                $t->timestamps();
            });
        }
        Schema::table('pod_phien', function (Blueprint $t) {
            if (!Schema::hasColumn('pod_phien', 'nhan_vien_id')) $t->unsignedBigInteger('nhan_vien_id')->nullable()->index();
        });
        Schema::table('pod_don', function (Blueprint $t) {
            if (!Schema::hasColumn('pod_don', 'nhan_vien_id')) $t->unsignedBigInteger('nhan_vien_id')->nullable()->index();
        });
        Schema::table('pod_seller', function (Blueprint $t) {
            if (!Schema::hasColumn('pod_seller', 'noi_giao_mac_dinh')) $t->unsignedBigInteger('noi_giao_mac_dinh')->nullable();
            if (!Schema::hasColumn('pod_seller', 'doi_mk_luc')) $t->timestamp('doi_mk_luc')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pod_nhan_vien');
        Schema::table('pod_phien', fn (Blueprint $t) => $t->dropColumn('nhan_vien_id'));
        Schema::table('pod_don', fn (Blueprint $t) => $t->dropColumn('nhan_vien_id'));
        Schema::table('pod_seller', fn (Blueprint $t) => $t->dropColumn(['noi_giao_mac_dinh', 'doi_mk_luc']));
    }
};
