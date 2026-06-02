<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sizes', function (Blueprint $table) {
            // Cân nặng (gram) của 1 sản phẩm ở khổ này, dùng tính phí ship & tạo vận đơn.
            $table->unsignedInteger('weight')->default(500)->after('price');
        });
    }

    public function down(): void
    {
        Schema::table('sizes', function (Blueprint $table) {
            $table->dropColumn('weight');
        });
    }
};
