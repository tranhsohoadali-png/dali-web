<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Đơn giá riêng của một mẫu (đ/gram, mọi nhựa): dùng THAY đơn giá chung PETG/PLA, rồi mới cộng tang_gia (%).
 * NULL = theo đơn giá chung. Vd bộ book nook cửa sổ Gothic 500đ/gram.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pod_mau', function (Blueprint $t) {
            if (!Schema::hasColumn('pod_mau', 'gia_gram')) $t->unsignedInteger('gia_gram')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pod_mau', fn (Blueprint $t) => $t->dropColumn('gia_gram'));
    }
};
