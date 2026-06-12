<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Khách trang /thiet-ke: nhập SĐT để LƯU bản thiết kế theo số điện thoại
 * (hiện danh sách + xuất file trong admin).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('design_leads', function (Blueprint $t) {
            $t->id();
            $t->string('phone', 30)->index();
            $t->string('device_id', 64)->default('');
            $t->text('original_url')->nullable();
            $t->text('enhanced_url')->nullable();
            $t->text('result_url')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('design_leads');
    }
};
