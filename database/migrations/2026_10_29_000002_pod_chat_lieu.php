<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Chất liệu theo từng phân loại: PETG (nhựa chính của xưởng, 900đ/gram) hoặc PLA (1.300đ/gram).
 * Giá gram theo chất liệu nằm ở cau_hinh_3d (POD_GIA_GRAM_PETG, POD_GIA_GRAM = PLA).
 * Dòng đơn chụp lại chất liệu lúc đặt, cạnh don_gia_gram.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pod_bien_the', function (Blueprint $t) {
            if (!Schema::hasColumn('pod_bien_the', 'chat_lieu')) $t->string('chat_lieu', 10)->default('PETG');
        });
        Schema::table('pod_don_dong', function (Blueprint $t) {
            if (!Schema::hasColumn('pod_don_dong', 'chat_lieu')) $t->string('chat_lieu', 10)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pod_bien_the', fn (Blueprint $t) => $t->dropColumn('chat_lieu'));
        Schema::table('pod_don_dong', fn (Blueprint $t) => $t->dropColumn('chat_lieu'));
    }
};
