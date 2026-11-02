<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Cấp thêm quyền xem + đặt một mẫu riêng cho seller ngoài chủ mẫu. Không có dòng nào = chỉ chủ mẫu thấy.
 * Bảng chỉ có nghĩa với mẫu riêng: mẫu Kho chung mọi seller đã thấy.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pod_mau_cap', function (Blueprint $t) {
            $t->id();
            $t->foreignId('mau_id')->constrained('pod_mau')->cascadeOnDelete();
            $t->foreignId('seller_id')->constrained('pod_seller')->cascadeOnDelete();
            $t->timestamps();
            $t->unique(['mau_id', 'seller_id']);
            $t->index('seller_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pod_mau_cap');
    }
};
