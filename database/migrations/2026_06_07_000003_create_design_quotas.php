<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hạn mức "tạo kết quả AI" cho khách trang /thiet-ke, đếm theo device id.
        Schema::create('design_quotas', function (Blueprint $table) {
            $table->id();
            $table->string('device_id', 64)->unique();
            $table->unsignedInteger('used')->default(0);    // đã dùng
            $table->unsignedInteger('bonus')->default(0);   // thưởng (vd +5 sau đặt hàng)
            $table->string('last_ip', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('design_quotas');
    }
};
