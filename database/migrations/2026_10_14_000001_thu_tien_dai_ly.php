<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Thu tiền đại lý TỪNG PHẦN: nhập số tiền thực nhận -> trừ vào đơn đi hộ CŨ NHẤT trước (đơn đủ tiền thì "Đã thu"),
 * phần lẻ không đủ trọn 1 đơn giữ ở dai_ly.tra_truoc để trừ lần sau. Mỗi lần thu là 1 dòng sổ (huỷ được lần gần nhất).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dai_ly', function (Blueprint $t) {
            if (!Schema::hasColumn('dai_ly', 'tra_truoc')) $t->unsignedInteger('tra_truoc')->default(0);
        });
        if (!Schema::hasTable('dai_ly_thu_tien')) {
            Schema::create('dai_ly_thu_tien', function (Blueprint $t) {
                $t->id();
                $t->unsignedBigInteger('dai_ly_id')->index();
                $t->unsignedInteger('so_tien');                 // tiền thực nhận lần này
                $t->string('ghi_chu', 200)->nullable();
                $t->json('don_ids')->nullable();                // các đơn được đánh dấu Đã thu nhờ lần này (để huỷ)
                $t->unsignedInteger('tra_truoc_truoc')->default(0);
                $t->unsignedInteger('tra_truoc_sau')->default(0);
                $t->boolean('huy')->default(false);
                $t->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('dai_ly_thu_tien');
        Schema::table('dai_ly', fn (Blueprint $t) => $t->dropColumn('tra_truoc'));
    }
};
