<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Bảng sản phẩm cho khu "Xưởng in 3D".
 * Tách riêng khỏi bảng `products` (tranh) — hai dòng hàng quản lý độc lập,
 * chung một đăng nhập admin. Đây là bản sao đầy đủ của mô hình san_pham
 * bên PocketBase để chuyển hẳn dữ liệu về Laravel.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sp_3d', function (Blueprint $t) {
            $t->id();
            $t->string('slug')->unique();
            $t->string('ten');
            $t->string('art')->default('');            // emoji dự phòng khi chưa có ảnh
            $t->string('cat')->default('');            // nhóm hàng (chữ tự do)
            $t->integer('gia')->default(0);
            $t->integer('gia_goc')->default(0);        // giá gạch ngang, 0 = không có
            $t->string('nhan')->default('');           // nhãn góc ảnh: BÁN CHẠY NHẤT…
            $t->string('mo_ta_ngan', 300)->default('');
            $t->text('mo_ta_dai')->nullable();
            $t->json('mota')->nullable();              // các gạch đầu dòng mô tả
            $t->json('variants')->nullable();          // phân loại [{ten,gia}]
            $t->json('anh')->nullable();               // mảng đường dẫn ảnh, [0] là ảnh bìa
            $t->boolean('khac_ten')->default(false);   // có khắc tên miễn phí
            $t->boolean('dat_lam')->default(false);    // hàng đặt làm, cọc 50%
            $t->decimal('sao', 2, 1)->default(0);      // điểm sao (cho phép 4.9)
            $t->integer('da_ban')->default(0);
            $t->integer('kho')->default(0);            // tồn kho, 0 = không theo dõi
            $t->integer('thu_tu')->default(0);         // nhỏ đứng trước
            $t->boolean('hien')->default(true);
            $t->string('payment_policy')->default(''); // cod_or_prepaid_10 / deposit_50
            $t->string('shipping_class')->default('');
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sp_3d');
    }
};
