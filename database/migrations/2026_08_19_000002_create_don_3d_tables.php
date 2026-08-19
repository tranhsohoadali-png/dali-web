<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Khu "Xưởng in 3D" — đơn hàng, sự kiện, cấu hình giá.
 * Chép nguyên mô hình từ PocketBase (don_hang / analytics_event / cau_hinh)
 * để chuyển hẳn cỗ máy checkout sang Laravel. Tách riêng, không đụng orders (tranh).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('don_3d', function (Blueprint $t) {
            $t->id();
            $t->string('ma')->unique();                 // mã đơn DLxxxxxx-YYYY
            $t->string('client_ref')->unique();         // chống trùng đơn
            $t->string('loai')->default('cod');         // cod / ck / coc
            $t->string('tt')->default('cho_coc');        // trạng thái xử lý
            $t->string('trang_thai_tt')->default('cho_xac_nhan'); // trạng thái thanh toán
            $t->text('sp')->nullable();                  // tóm tắt sản phẩm
            $t->json('chi_tiet')->nullable();            // từng dòng hàng
            $t->integer('tong')->default(0);
            $t->integer('giam')->default(0);
            $t->integer('phi_ship')->default(0);
            $t->integer('tra_ngay')->default(0);         // phải trả ngay (cọc/QR)
            $t->integer('con_lai')->default(0);
            $t->string('phuong_thuc_tt')->default('cod'); // cod / qr
            $t->string('shipping_method')->default('standard');
            $t->string('nguon')->default('website-v2');
            $t->string('ten')->default('');
            $t->string('sdt')->default('');
            $t->string('email')->default('');
            $t->string('tinh')->default('');
            $t->text('dia_chi')->nullable();
            $t->text('ghi_chu')->nullable();
            $t->string('ma_vc')->default('');            // mã vận chuyển
            $t->string('vc')->default('');               // hãng vận chuyển
            $t->timestamp('xong_luc')->nullable();
            $t->timestamps();
        });

        Schema::create('su_kien_3d', function (Blueprint $t) {
            $t->id();
            $t->string('event');
            $t->string('path')->default('');
            $t->timestamps();
        });

        Schema::create('cau_hinh_3d', function (Blueprint $t) {
            $t->string('khoa')->primary();
            $t->json('gia_tri');
            $t->timestamps();
        });

        // Nhồi cấu hình hiện tại (lấy đúng từ PocketBase) để tính tiền khớp từng đồng.
        $now = now();
        DB::table('cau_hinh_3d')->insert([
            ['khoa' => 'GIA', 'gia_tri' => json_encode([
                'full' => 289000, 'board' => 165000, 'phieu' => 8000, 'custom' => 12000,
                'chan' => 15000, 'boPhieu' => 149000, 'shipPhieu' => 15000,
                'freeshipPhieu' => 99000, 'minPhieu' => 29000,
            ], JSON_UNESCAPED_UNICODE), 'created_at' => $now, 'updated_at' => $now],
            ['khoa' => 'BANK', 'gia_tri' => json_encode([
                'bin' => 'MSB', 'stk' => '03001011945010', 'ten' => 'DOAN ANH TUAN',
            ], JSON_UNESCAPED_UNICODE), 'created_at' => $now, 'updated_at' => $now],
            ['khoa' => 'MON', 'gia_tri' => '[["Tiếng Việt","#f9c3c3"],["Toán","#ee2a33"],["Tiếng Anh","#fff700"],["Ôn Toán","#ee2a33"],["Ôn TV","#2e3192"],["HĐTN","#17939f"],["HĐTN 1","#17939f"],["HĐTN 2","#17939f"],["HĐTN 3","#17939f"],["GDĐP","#1f7a44"],["Âm Nhạc","#2faebe"],["TN \\u0026 XH","#46b428"],["GDTC","#6f6f6f"],["Đạo Đức","#e0921f"],["Mĩ Thuật","#d02bc0"],["KN Sống","#6e7e87"],["LS \\u0026 ĐL","#7a4a21"],["Tin Học","#1a1a1a"],["Khoa Học","#9550db"],["Công Nghệ","#e07e2b"],["Chào Cờ","#5b4bd6"],["SH Lớp","#f07e2b"]]', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('don_3d');
        Schema::dropIfExists('su_kien_3d');
        Schema::dropIfExists('cau_hinh_3d');
    }
};
