<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('orders', function (Blueprint $table) {
            // true = đã hoàn lại hoa hồng CTV khi đơn bị huỷ/xoá (chống hoàn trùng).
            $table->boolean('commission_reversed')->default(false)->after('affiliate_commission');
        });
    }
    public function down(): void {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('commission_reversed');
        });
    }
};
