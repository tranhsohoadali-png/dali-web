<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Nhập đơn từ file seller: nhớ "tên sản phẩm + kích thước trong file của seller" -> phân loại của xưởng,
 * để lần nhập sau tự chọn sẵn (vd Thỏ Nâu: "Frosted Frown Plush Keychain | 4.5cm" -> Grinch áo xanh 1.8 inch).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('pod_anh_xa')) return;
        Schema::create('pod_anh_xa', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('seller_id')->index();
            $t->string('khoa', 255);                 // PodNhapDon::khoa(tên, kích thước)
            $t->unsignedBigInteger('bien_the_id');
            $t->string('mau_sac', 60)->nullable();   // màu mặc định khi file không ghi màu
            $t->timestamps();
            $t->unique(['seller_id', 'khoa']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pod_anh_xa');
    }
};
