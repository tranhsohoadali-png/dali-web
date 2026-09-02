<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Giá sỉ cho sản phẩm 3D + tài khoản đại lý (xem giá sỉ trên web). */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('sp_3d', 'gia_si')) {
            Schema::table('sp_3d', function (Blueprint $t) {
                $t->integer('gia_si')->default(0)->after('gia_goc'); // 0 = chưa đặt giá sỉ
            });
        }

        if (!Schema::hasTable('dai_ly')) {
            Schema::create('dai_ly', function (Blueprint $t) {
                $t->id();
                $t->string('ten');
                $t->string('sdt')->unique();
                $t->string('matkhau');                 // đã hash
                $t->string('token', 64)->nullable()->index(); // phiên đăng nhập hiện tại
                $t->boolean('hien')->default(true);    // false = khoá đại lý
                $t->text('ghi_chu')->nullable();
                $t->timestamp('dang_nhap_luc')->nullable();
                $t->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('dai_ly');
        if (Schema::hasColumn('sp_3d', 'gia_si')) {
            Schema::table('sp_3d', fn (Blueprint $t) => $t->dropColumn('gia_si'));
        }
    }
};
