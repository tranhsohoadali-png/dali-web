<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Chỉ bán dạng tổng hợp (combo): ẩn sản phẩm lẻ khỏi trang Sản phẩm + trang chủ,
            // chỉ hiển thị trang combo /chu-de/{slug}. Sản phẩm vẫn là "mẫu" trong combo.
            $table->boolean('combo_only')->default(false)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('combo_only');
        });
    }
};
